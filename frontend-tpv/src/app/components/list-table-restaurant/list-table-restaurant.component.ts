import { CommonModule } from "@angular/common";
import { Component, OnInit, ChangeDetectorRef, OnDestroy } from "@angular/core";
import { IonicModule, AlertController } from '@ionic/angular';
import { Router } from "@angular/router";
import { Order } from "src/app/models/Order";
import { Table } from "src/app/models/Table";
import { LocationService } from "src/app/services/location.service";
import { OrderService } from "src/app/services/order.service";
import { TableService } from "src/app/services/table.service";
import { Location } from "src/app/models/Location";
import { FormsModule } from "@angular/forms";
import { Product } from "src/app/models/Product";
import { ProductService } from "src/app/services/product.service";
import { Subject, takeUntil } from 'rxjs';
import { OrderLineService } from "src/app/services/order-line.service";
import { OrderLine } from "src/app/models/OrderLine";
import { Employee } from "src/app/models/Employee";
import { EmployeeRol } from "src/app/models/Employee-rol";
import { AlertService } from "src/app/services/alert.service";


@Component({
  selector: 'app-list-table-restaurant',
  templateUrl: './list-table-restaurant.component.html',
  styleUrls: ['./list-table-restaurant.component.scss'],
  standalone: true,
  imports: [IonicModule, CommonModule, FormsModule],
})
export class ListTableRestaurantComponent implements OnInit, OnDestroy {
  idRestaurant!: number;
  listTables: Table[] = [];
  filterTables: Table[] = [];
  listLocations: Location[] = [];
  products: Product[] = [];
  restaurantName!: string;
  selectedLocationId?: number;
  order!: Order;
  guests: number = 0;
  showGuestsModal: boolean = false;
  idTableSelected: number = 0;
  gridCells: { row: number; col: number }[] = [];
  listAllTables: Table[] = [];
  employeeRol!: EmployeeRol;
  firefox: boolean = false;

  recognition: any;
  comandoVoz: string = '';
  private readonly ngUnsubscribe = new Subject();
  isListening: boolean = false; // Añadido
  speechRecognitionAvailable: boolean = false;

  constructor(
    private tableService: TableService,
    private orderService: OrderService,
    private orderLineService: OrderLineService,
    private locationService: LocationService,
    private productService: ProductService,
    private router: Router,
    private cdr: ChangeDetectorRef,
    private alertController: AlertController,
    private alertService: AlertService
  ) {
    this.initSpeechRecognition();
  }

  ngOnInit() {
    const storedRestaurantId = localStorage.getItem('idRestaurant');
    const employeeRol = localStorage.getItem('employeeRol');
    if(employeeRol){
      const plain = JSON.parse(employeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
    }
    if (!storedRestaurantId) {
      this.router.navigate(['/loginRestaurant']);
      return;
    }else{
      if(!this.employeeRol){
        this.router.navigate(['/employees']);
      }
      if(!this.employeeRol.tpv){
        this.router.navigate(['/employees/panel']);
      }
    }

    if (navigator.userAgent.includes("Firefox") && !navigator.userAgent.includes("Seamonkey")) {
    this.firefox = true;
    } 

    this.restaurantName = localStorage.getItem('restaurant') ?? '';
    this.idRestaurant = Number(storedRestaurantId);

    this.listOfLocations();
    this.listOfProducts(); // Cargamos la lista de productos al inicio
    this.generateGridCells();
  }

  ionViewWillEnter() {
    this.idRestaurant = Number(localStorage.getItem('idRestaurant'));
    this.restaurantName = localStorage.getItem('restaurant') ?? '';
    if (navigator.userAgent.includes("Firefox") && !navigator.userAgent.includes("Seamonkey")) {
    this.firefox = true;
    } 
    this.listOfLocations();
    this.generateGridCells();
  }

  ngOnDestroy(): void {
    this.ngUnsubscribe.next(null); // Añadimos 'null' como argumento
    this.ngUnsubscribe.complete();
    if (this.recognition) {
      this.recognition.stop();
    }
  }

  initSpeechRecognition() {
    const SpeechRecognition = (window as any).webkitSpeechRecognition || (window as any).SpeechRecognition;
    const isFirefox = navigator.userAgent.toLowerCase().includes('firefox');

    if (SpeechRecognition && !isFirefox) { // Solo intentar inicializar en navegadores compatibles (no Firefox en este caso)
      this.speechRecognitionAvailable = true;
      this.recognition = new SpeechRecognition();
      this.recognition.lang = 'es-ES';
      this.recognition.interimResults = false;
      this.recognition.maxAlternatives = 1;

      this.recognition.onresult = (event: any) => {
        this.comandoVoz = event.results[0][0].transcript.toLowerCase().trim();
        this.comandoVoz = this.convertirPalabrasANumero(this.comandoVoz);
        this.procesarComandoVoz();
      };

      this.recognition.onerror = async (event: any) => {
        if (event.error === 'not-allowed') {
          await this.presentAlertMicro();
          this.isListening = false;
          return;
        }else{
          this.recognition.onend = () => {
            if (this.isListening) {
              this.iniciarEscucha();
            }
          };
        }
      };



    } else {
      if(this.firefox = false){
      this.alertService.show('Lo sentimos', 'En estos momentos, esta funcion no está disponible.', 'warning');
      }
      this.speechRecognitionAvailable = false;
      //this.comandoVoz = 'tpv añade una hamburguesa a mesa 3';
      //this.procesarComandoVoz();
    }
  }

  convertirPalabrasANumero(texto: string): string {
    const mapaNumeros: { [key: string]: number } = {
      'uno': 1, 'una': 1, 'dos': 2, 'tres': 3, 'cuatro': 4, 'cinco': 5, 'seis': 6, 'siete': 7,
      'ocho': 8, 'nueve': 9, 'diez': 10, 'once': 11, 'doce': 12, 'trece': 13, 'catorce': 14,
      'quince': 15, 'dieciséis': 16, 'diecisiete': 17, 'dieciocho': 18, 'diecinueve': 19, 'veinte': 20,
      'veintiuno': 21, 'veintidós': 22, 'veintidos': 22, 'veintitrés': 23, 'veintitres': 23,
      'veinticuatro': 24, 'veinticinco': 25, 'veintiséis': 26, 'veintiseis': 26, 'veintisiete': 27,
      'veintiocho': 28, 'veintinueve': 29, 'treinta': 30, 'treinta y uno': 31, 'treinta y dos': 32,
      'treinta y tres': 33, 'treinta y cuatro': 34, 'treinta y cinco': 35, 'treinta y seis': 36,
      'treinta y siete': 37, 'treinta y ocho': 38, 'treinta y nueve': 39, 'cuarenta': 40,
      'cuarenta y uno': 41, 'cuarenta y dos': 42, 'cuarenta y tres': 43, 'cuarenta y cuatro': 44,
      'cuarenta y cinco': 45, 'cuarenta y seis': 46, 'cuarenta y siete': 47, 'cuarenta y ocho': 48,
      'cuarenta y nueve': 49, 'cincuenta': 50, 'cincuenta y uno': 51, 'cincuenta y dos': 52,
      'cincuenta y tres': 53, 'cincuenta y cuatro': 54, 'cincuenta y cinco': 55, 'cincuenta y seis': 56,
      'cincuenta y siete': 57, 'cincuenta y ocho': 58, 'cincuenta y nueve': 59, 'sesenta': 60,
      'sesenta y uno': 61, 'sesenta y dos': 62, 'sesenta y tres': 63, 'sesenta y cuatro': 64,
      'sesenta y cinco': 65, 'sesenta y seis': 66, 'sesenta y siete': 67, 'sesenta y ocho': 68,
      'sesenta y nueve': 69, 'setenta': 70
    };

    const palabras = texto.split(' ');

    return palabras.map((palabra) => {
      const actual = palabra.toLowerCase();
      return mapaNumeros[actual]?.toString() || palabra;
    }).filter(Boolean).join(' ');
  }


  iniciarEscucha() {
    if (this.speechRecognitionAvailable && this.recognition) { // Solo iniciar si la API está disponible
      this.comandoVoz = '';
      this.recognition.start();
    } else {
      this.alertService.show('Lo sentimos', 'En estos momentos, esta funcion no está disponible.', 'warning');
    }
  }
  async procesarComandoVoz() {
    let mesaEncontradaId: number | null = null;
    let productoEncontradoId: number | null = null;
    let cantidad = 1; // Cantidad por defecto

    if(this.comandoVoz === ""){
      this.alertService.show('Ups...', 'No he podido entender lo que has dicho.', 'info');
    }
    // Buscar la mesa por nombre completo
    for (const mesa of this.listAllTables) {
        if (this.comandoVoz.toLowerCase().includes(mesa.mesa!.toLowerCase())) {
            mesaEncontradaId = mesa.id!;
            break;
        }
    }

    // Si se encontró la mesa, proceder con el análisis del comando
    if (mesaEncontradaId) {
        // Dividir el comando en partes
        const comandoPartes = this.comandoVoz.split(' ');

        // Eliminar la parte que menciona la mesa
        comandoPartes.forEach((parte, index) => {
            if (parte.toLowerCase().includes("mesa")) {
                comandoPartes.splice(index, 2); // Eliminar la palabra "mesa" y el número correspondiente
            }
        });

        // Ahora, buscar el producto (el resto de la frase)
        for (const producto of this.products) {
            // Compara el resto del comando con el nombre del producto
            if (comandoPartes.join(' ').toLowerCase().includes(producto.nombre!.toLowerCase())) {
                productoEncontradoId = producto.id!;
                break;
            }
        }

        // Ahora revisar si alguna parte de la cadena es un número (cantidad)
        for (const parte of comandoPartes) {
            const cantidadPosible = parseInt(parte, 10);
            if (!isNaN(cantidadPosible) && cantidadPosible > 0) {
                cantidad = cantidadPosible;
                break; // Si encontramos un número, lo tomamos como la cantidad
            }
        }

        // Si se encontró el producto, proceder con la creación de la línea de pedido
        if (productoEncontradoId) {
            this.crearLineaPedidoVoz(mesaEncontradaId, productoEncontradoId, cantidad);
        } else {
          this.alertService.show('Lo siento', 'No le he entendido, por favor vuelve a repetirlo.', 'warning');
          this.isListening = false;
        }
    } else {
        this.alertService.show('Lo siento', 'No le he entendido, por favor vuelve a repetirlo.', 'warning');
        this.isListening = false;
    }
  }





  crearLineaPedidoVoz(idMesa: number, idProducto: number, cantidad: number) {
    this.orderService.getOrderTable(idMesa).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
      next: (order) => {
        let orderId = order?.id;
        if (!orderId || orderId <= 0) {
          const newOrder = new Order({ comensales: 1, idMesa: idMesa });
          this.orderService.postOrder(newOrder).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
            next: (newOrderData) => {
              orderId = newOrderData.id!;
              this.addLineByVoice(orderId, idProducto, cantidad);
            },
            error: (err) => this.alertService.show('Error', 'Error al crear el pedido.', 'error'),
          });
        } else {
          this.addLineByVoice(orderId, idProducto, cantidad);
        }
      },
      error: (err) => this.alertService.show('Error', 'No se pudo obtener el pedido.', 'error'),
    });
  }

  addLineByVoice(idPedido: number, idProducto: number, cantidad: number) {
    // Buscar el producto por su ID para obtener el precio y el nombre
    const productToAdd = this.products.find(p => p.id === idProducto);
    if (productToAdd) {
      const newLine: OrderLine = {
        id: -1, // El ID se genera en el backend
        idPedido: idPedido,
        idProducto: productToAdd.id!,
        cantidad: cantidad,
        precio: productToAdd.precio!,
        nombre: productToAdd.nombre!,
        observaciones: ' ', // Puedes dejarlo vacío o permitir especificarlo por voz
        estado: 'Pendiente',
      };

      this.orderLineService.postOrderLines(newLine).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
        next: (response) => {

          this.showAlert("Comanda", "Se ha añadido " + response.cantidad?.toString()! + " de " + response.nombre);
        },
      });
    } else {
      this.showAlert("Ups...", "No he podido entender lo que has dicho");
      // Aquí podrías dar feedback visual al usuario sobre el producto no encontrado
    }
  }

  async showAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header,
      message,
      buttons: ['OK'],
    });

    await alert.present();
  }

  toggleListen() {
    this.isListening = !this.isListening;
    if (this.isListening) {
      this.iniciarEscucha();
    } else {
      if (this.speechRecognitionAvailable && this.recognition) { // Solo detener si la API está disponible
        this.recognition.stop();
      }
      this.comandoVoz = ''; // Limpiar después
    }
  }

  listOfLocations() {
    this.locationService.getListLocationRestaurant(this.idRestaurant).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
      next: (data) => {
        this.listLocations = data.filter((loc) => !!loc.activo);
        if (this.listLocations.length > 0) {
          this.selectedLocationId = this.listLocations[0].id;
          this.loadTablesByLocation(this.selectedLocationId!);
        }
      },
      error: () => this.router.navigate(['/loginRestaurant']),
    });
  }

  loadTablesByLocation(locationId: number) {
    this.tableService.getListTableRestaurant(this.idRestaurant).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
      next: (data) => {
        this.listAllTables = data.filter(
          (tab) => tab.activo
        );
        this.listTables = data.filter(
          (tab) => tab.activo && tab.idUbicacion === locationId
        );
        this.filterTables = this.sortTablesByName(this.listTables);
        this.showStatus();
      },
      error: () => this.router.navigate(['/loginRestaurant']),
    });
  }

  listOfTables() {
    this.tableService.getListTableRestaurant(this.idRestaurant).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
      next: (data) => {
        this.listTables = data.filter((tab) => tab.activo === true);
        this.filterTables = this.sortTablesByName(this.listTables);
        this.showStatus();
      },
      error: () => this.router.navigate(['/loginRestaurant']),
    });
  }

  getRow(table: Table) {
    return table.pos_y! + 1;
  }

  getColumn(table: Table) {
    return table.pos_x! + 1;
  }

  filterLocation(idUbicacion?: number) {
    if (this.selectedLocationId === idUbicacion) return;
    this.selectedLocationId = idUbicacion;
    if (idUbicacion) {
      this.loadTablesByLocation(idUbicacion);
    }
  }

  sortTablesByName(tables: Table[]): Table[] {
    return tables.slice().sort((a, b) => {
      const aNum = parseInt(a.mesa || '', 10);
      const bNum = parseInt(b.mesa || '', 10);
      return (isNaN(aNum) || isNaN(bNum)) ? a.mesa!.localeCompare(b.mesa!) : aNum - bNum;
    });
  }

  showStatus() {
    for (const element of this.filterTables) {
      this.orderService.getOrderTable(element.id!).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
        next: (order) => {
          if (order?.id && order.id > 0) {
            element.estado = 'ocupada';
            element.nComensales = order.comensales;
          } else {
            element.estado = 'libre';
            element.nComensales = 0;
          }
        },
        error: (err) => {
        },
      });
    }
  }

  clickTable(idTable?: number, tableName?: string) {
    localStorage.removeItem('idTable');
    localStorage.removeItem('order');
    localStorage.removeItem('tableName');
    if (!idTable) return;
    this.idTableSelected = idTable;
    this.orderService.getOrderTable(idTable).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
      next: (order) => {
        if (order?.id && order.id > 0) {
          this.order = order;
          localStorage.setItem('idTable', idTable.toString());
          localStorage.setItem('order', JSON.stringify(this.order));
          localStorage.setItem('tableName', tableName!);
          this.router.navigate(['/tpv']);
        } else {
          this.showGuestsModal = true;
        }
      },
      error: (err) => {
        this.alertService.show('Error', 'No se pudo obtener el pedido.', 'error');
      },
    });
  }

  increaseGuests() {
    this.guests++;
  }

  decreaseGuests() {
    if (this.guests > 1) this.guests--;
  }

  confirmGuests(id: number) {
    const newOrder = new Order({
      comensales: this.guests,
      idMesa: this.idTableSelected,
    });
    this.orderService.postOrder(newOrder).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
      next: (data) => {
        localStorage.setItem('idTable', this.idTableSelected.toString());
        localStorage.setItem('order', JSON.stringify(data));
        this.router.navigate(['/tpv']);
      },
      error: (err) => {
        this.alertService.show('Error', 'Error al crear el pedido.', 'error');
      },
    });

    this.showGuestsModal = false;
  }

  getTablesAt(rowIndex: number, colIndex: number): Table[] {
    return this.filterTables.filter(
      (table) => table.pos_x === colIndex && table.pos_y === rowIndex
    );
  }

  generateGridCells() {
    this.gridCells = [];
    for (let row = 0; row < 8; row++) {
      for (let col = 0; col < 10; col++) {
        this.gridCells.push({ row, col });
      }
    }
  }

  allowDrop(event: DragEvent) {
    event.preventDefault();
  }

  onDragStart(event: DragEvent, table: Table | undefined) {
    if (table) {
      event.dataTransfer?.setData('table', table.id!.toString());
    }
  }

  onDragEnd(event: DragEvent) {

  }

  listOfProducts() {
    this.productService.getListProductsRestaurant(this.idRestaurant).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
      next: (data) => {
        this.products = data;

      },
      error: (err) => {
        // this.router.navigate(['/loginRestaurant']);
      },
    });
  }

  dropTable(event: DragEvent, rowIndex: number, colIndex: number) {
    event.preventDefault();
    const tableId = event.dataTransfer?.getData('table');
    if (!tableId) return;

    const draggedTable = this.filterTables.find(table => table.id === Number(tableId));
    if (!draggedTable) return;

    const element = document.getElementById('table-' + draggedTable.id);
    element?.classList.add('drop-animate');
    setTimeout(() => element?.classList.remove('drop-animate'), 400);

    draggedTable.pos_x = colIndex;
    draggedTable.pos_y = rowIndex;

    if (!draggedTable.id || !draggedTable.mesa || !draggedTable.idUbicacion) {
      return;
    }

    const updatePayload: Table = {
      id: draggedTable.id,
      mesa: draggedTable.mesa,
      activo: draggedTable.activo,
      idUbicacion: draggedTable.idUbicacion,
      pos_x: draggedTable.pos_x,
      pos_y: draggedTable.pos_y,
      estado: draggedTable.estado ?? 'libre',
      nComensales: draggedTable.nComensales ?? 0,
    };

    this.tableService.updateTable(updatePayload).pipe(takeUntil(this.ngUnsubscribe)).subscribe({
      next: () => {
        
        this.cdr.detectChanges(); // 🔁 Forzar actualización de vista
      },
      error: (err) => {
        
      },
    });
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Fallo al añadir el pedido',
      message: 'Por favor, vuelva a repetir la comanda',
      buttons: ['Vale'],
    });

    await alert.present();
  }
  async presentAlertMicro() {
    const alert = await this.alertController.create({
      header: 'Acceso al micro denegado',
      message: 'Por favor, dale permiso al microfono',
      buttons: ['Vale'],
    });

    await alert.present();
  }
}

import { Component, OnInit, ViewChild, OnChanges, SimpleChanges, HostListener } from '@angular/core';
import { OrderLine } from 'src/app/models/OrderLine';
import { CommonModule } from '@angular/common';
import { IonicModule, AlertController } from '@ionic/angular';
import { HeaderTpvComponent } from '../header-tpv/header-tpv.component';
import { ProductsListComponent } from '../products-list/products-list.component';
import { CategoriesListComponent } from '../categories-list/categories-list.component';
import { OrderLineListComponent } from '../order-line-list/order-line-list.component';
import { TpvKeyboardComponent } from '../tpv-keyboard/tpv-keyboard.component';
import { OrderLineService } from 'src/app/services/order-line.service';
import { Router } from '@angular/router';
import { Order } from 'src/app/models/Order';
import { AfterViewInit } from '@angular/core';
import { OrderService } from 'src/app/services/order.service';
import { EmployeeRol } from 'src/app/models/Employee-rol';

@Component({
  selector: 'app-tpv',
  templateUrl: './tpv.component.html',
  standalone: true,
  styleUrls: ['./tpv.component.scss'],
  imports: [
    IonicModule,
    CommonModule,
    ProductsListComponent,
    CategoriesListComponent,
    OrderLineListComponent,
    TpvKeyboardComponent,
  ],
})
export class TpvComponent implements OnInit, OnChanges {
  @ViewChild('orderList') orderLineListComponent!: OrderLineListComponent;
  orderLines: OrderLine[] = [];
  currentEditingLine?: OrderLine;
  editingMode: 'cantidad' | 'total' = 'cantidad';
  currentInputValue: string = '';
  idPedido?: number = 0;
  order!: Order;
  showGuestsModal = false;
  guests!: number;
  tableName!: string;
  private debounceMap = new Map<number, ReturnType<typeof setTimeout>>();
  panelVisible = false;
  showKeyboard = false;
  isMobile = false;
  selectedCategoryId?: number;
  employeeRol!: EmployeeRol;

  constructor(
    private orderLineService: OrderLineService,
    private orderService: OrderService,
    private router: Router,
    private alertController: AlertController // Inyectar AlertController para mostrar alertas
  ) {}

  ngOnInit() {
    this.checkScreenSize();
    this.loadOrderData();
  }

  ionViewWillEnter() {
    this.loadOrderData();
    this.loadOrderLines();
  }

  loadOrderData() {
    

    const storedOrder = localStorage.getItem('order');
    if (storedOrder) {
      const plain = JSON.parse(storedOrder);
      this.order = Order.fromJSON(plain);
      this.idPedido = this.order.id;
      this.guests = this.order.comensales!;
      this.tableName = localStorage.getItem('tableName')!;
    } else {
      // Usamos IonAlert en lugar de alert tradicional
      this.showAlert('Error', 'No se encontró la orden en el almacenamiento local.');
      this.router.navigate(['/']);  // Redirigir a la página principal
    }
  }

  onRefreshOrderLine(): void {
    this.orderLineListComponent.refreshLines();
  }

  setEditingLine(orderLine: OrderLine): void {
    this.currentEditingLine = orderLine;
    this.updateCurrentInputValue();
  }

  setEditingMode(mode: 'cantidad' | 'total'): void {
    this.editingMode = mode;
    this.updateCurrentInputValue();
  }

  private updateCurrentInputValue(): void {
    if (!this.currentEditingLine) return;

    if (this.editingMode === 'cantidad') {
      this.currentInputValue = String(this.currentEditingLine.cantidad ?? '');
    } else {
      const total =
        (this.currentEditingLine.precio ?? 0) *
        (this.currentEditingLine.cantidad ?? 0);
      this.currentInputValue = total.toFixed(2);
    }
  }

  onKeyPressed(key: string): void {
    if (!this.currentEditingLine) return;
    if (key === 'keyboard') {
      this.showKeyboard = !this.showKeyboard;
      setTimeout(() => {
        this.adjustPanelHeight();
      }, 100);
      return;
    }

    if (key === '←') {
      this.currentInputValue = this.currentInputValue.slice(0, -1) || '0';
    } else {
      if (this.currentInputValue === '0') {
        this.currentInputValue = key; // reemplaza el 0 inicial
      } else {
        this.currentInputValue += key; // añade el nuevo dígito
      }
    }

    const parsedValue = parseFloat(this.currentInputValue.replace(',', '.'));
    if (isNaN(parsedValue)) return;

    if (this.editingMode === 'cantidad') {
      this.currentEditingLine.cantidad = parsedValue;
    } else {
      if (
        this.currentEditingLine.cantidad &&
        this.currentEditingLine.cantidad > 0
      ) {
        this.currentEditingLine.precio =
          parsedValue / this.currentEditingLine.cantidad;
      }
    }

    this.saveLineWithDebounce(this.currentEditingLine);
  }

  togglePanel() {
    this.panelVisible = !this.panelVisible;
    this.showKeyboard = false;
  }

  closePanel(event: Event) {
    event.stopPropagation();
    this.panelVisible = false;
    this.showKeyboard = false;
  }

  private adjustPanelHeight() {
    const panel = document.querySelector('.mobile-order-panel');
    if (panel) {
      panel.classList.toggle('keyboard-visible', this.showKeyboard);
    }
  }

  @HostListener('window:resize', ['$event'])
  onResize(event: any) {
    if (window.innerWidth > 768) {
      this.panelVisible = false;
      this.showKeyboard = false;
    }
    this.checkScreenSize();
  }

  private checkScreenSize() {
    this.isMobile = window.innerWidth <= 768;
  }

  saveLineWithDebounce(orderLine: OrderLine) {
    const lineId = orderLine.id;
    if (!lineId) return;

    if (this.debounceMap.has(lineId)) {
      clearTimeout(this.debounceMap.get(lineId));
    }

    const timeout = setTimeout(() => {
      this.orderLineService.putOrderLines(orderLine).subscribe({
        next: () => {
          this.onRefreshOrderLine();
        },
        error: (err) => {
          // Usamos IonAlert para mostrar un error
          this.showAlert('Error', 'Error al actualizar la línea de pedido. Intenta nuevamente.');
        },
      });

      this.debounceMap.delete(lineId);
    }, 100);

    this.debounceMap.set(lineId, timeout);
  }

  handlePayment() {
    this.router.navigate(['/payment']);
  }

  //MANEJO DE CATEGORIAS
  onCategorySelected(categoryId: number) {
    this.selectedCategoryId = categoryId;
  }

  onRefresh() {
    this.onRefreshOrderLine();
  }

  loadOrderLines() {
    if (!this.idPedido) {
      // Usamos IonAlert si no hay ID de pedido
      this.showAlert('Error', 'No hay ID de pedido para cargar las líneas.');
      return;
    }
    this.orderLineService.getListOrderLines(this.idPedido).subscribe({
      next: (data) => {
        this.orderLines = data;

        if (this.orderLines.length > 0) {
          const ultimaLinea = this.orderLines[this.orderLines.length - 1];
          this.setEditingLine(ultimaLinea);
        }
      },
      error: (err) => {
        // Usamos IonAlert en caso de error al cargar las líneas de pedido
        this.showAlert('Error', 'Error al cargar las líneas del pedido. Intenta nuevamente.');
      },
    });
  }

  increaseGuests() {
    this.guests++;
  }

  decreaseGuests() {
    if (this.guests > 1) this.guests--;
  }

  confirmGuests() {
    this.orderService.putOrderDiners(this.idPedido!, this.guests!).subscribe({
      next: (data) => {
        localStorage.setItem('order', JSON.stringify(data));
        this.order = data;

        // Usamos IonAlert para confirmar el cambio de comensales
        this.showAlert('Éxito', 'Número de comensales actualizado.');
      },
      error: (err) => {
        // Usamos IonAlert en caso de error al actualizar los comensales
        this.showAlert('Error', 'Error al actualizar el número de comensales. Intenta nuevamente.');
      },
    });
    this.showGuestsModal = false;
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['orderLines']) {
      // Si es necesario realizar alguna acción cuando cambian las líneas de pedido, agrega lógica aquí.
    }
  }

  refreshLines(): void {
    this.orderLineListComponent.listOfOrderLines(); // recarga desde el servicio
  }

  // Método para mostrar alertas de Ionic
  async showAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header,
      message,
      buttons: ['OK'],
    });

    await alert.present();
  }
}

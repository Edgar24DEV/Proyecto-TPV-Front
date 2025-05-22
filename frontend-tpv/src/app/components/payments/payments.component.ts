import {
  Component,
  EventEmitter,
  HostListener,
  OnInit,
  Output,
  ViewChild,
} from '@angular/core';
import { CommonModule, NgIf, NgFor } from '@angular/common';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { Router } from '@angular/router';

import { HeaderTpvComponent } from '../header-tpv/header-tpv.component';
import { OrderLineListComponent } from '../order-line-list/order-line-list.component';

import { OrderLine } from 'src/app/models/OrderLine';
import { Order } from 'src/app/models/Order';
import { OrderLineService } from 'src/app/services/order-line.service';
import { OrderService } from 'src/app/services/order.service';
import { TicketService } from 'src/app/services/ticket.service';
import { ClientService } from 'src/app/services/client.service';
import { Client } from 'src/app/models/Client';
import { RestaurantService } from 'src/app/services/restaurant.service';
import { PaymentService } from 'src/app/services/payment.service';
import { Payment } from 'src/app/models/Payment';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'app-payment',
  standalone: true,
  templateUrl: './payments.component.html',
  styleUrls: ['./payments.component.scss'],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    IonicModule,
    NgIf,
    NgFor,
    OrderLineListComponent,
    ReactiveFormsModule,
  ],
})
export class PaymentComponent implements OnInit {
  @ViewChild('orderList') orderLineListComponent!: OrderLineListComponent;
  orderLines: OrderLine[] = [];
  currentEditingLine?: OrderLine;
  editingMode: 'cantidad' | 'total' = 'cantidad';
  currentInputValue: string = '';
  idPedido?: number = 0;
  order!: Order;
  showGuestsModal = false;
  showPayModal = false;
  guests!: number;
  tableName!: string;
  total: number = 0;
  showPinModal = false;
  pinInputValue = '';
  private debounceMap = new Map<number, ReturnType<typeof setTimeout>>();
  restaurantName!: string;
  idRestaurant!: number;
  pin: string = '';
  pinLength: string = '';
  key: string = '';
  pay: string = '';
  tipo: string = '';
  restante: number = this.total;
  payments: Payment[] = [];
  isMobile!: boolean;
  employeeRol!: EmployeeRol;
  cifLength: boolean = true;
  idCompany!: number;

  // CLIENTES
  showClientModal = false;
  addingNewClient = false;
  clientSearchCif = '';
  foundClient: any = null;
  newClient: Client | null = null;
  clientForm!: FormGroup;

  alphanumericKeys: string[] = [
    '0',
    '1',
    '2',
    '3',
    '4',
    '5',
    '6',
    '7',
    '8',
    '9',
    'A',
    'B',
    'C',
    'D',
    'E',
    'F',
    'G',
    'H',
    'I',
    'J',
    'K',
    'L',
    'M',
    'N',
    'O',
    'P',
    'Q',
    'R',
    'S',
    'T',
    'U',
    'V',
    'W',
    'X',
    'Y',
    'Z',
  ];

  constructor(
    private orderLineService: OrderLineService,
    private orderService: OrderService,
    private ticketService: TicketService,
    private clientService: ClientService,
    private paymentService: PaymentService,
    private restaurantService: RestaurantService,
    private router: Router,
    private fb: FormBuilder,
    private alertService: AlertService
  ) {
    this.checkScreenWidth();
  }

  ngOnInit() {
    this.checkScreenWidth();
    this.loadOrderFromLocalStorage();
    this.clientForm = this.fb.group({
      razonSocial: ['', Validators.required],
      cif: ['', [Validators.required, Validators.pattern('^[A-Za-z0-9]+$')]],
      direccion: ['', Validators.required],
      email: ['', [Validators.email, Validators.required]],
    });
    const storedCompany = localStorage.getItem('idCompany');
    if (storedCompany) {
      this.idCompany = Number(storedCompany);
    }
    // Recupero el id Restaurante:
    const storedRestaurantId = localStorage.getItem('idRestaurant');
    if (!storedRestaurantId && !storedCompany) {
      this.router.navigate(['/loginRestaurant']);
    }

    const storedRestaurantName = localStorage.getItem('restaurant');
    if (storedRestaurantName) {
      this.restaurantName = storedRestaurantName;
    }
    this.idRestaurant = Number(storedRestaurantId);

    const storedEmployeeRol = localStorage.getItem('employeeRol');
    if (storedEmployeeRol) {
      const plain = JSON.parse(storedEmployeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
    }

    
    if (!storedCompany || this.idRestaurant) {
      if (!this.employeeRol) {
        this.router.navigate(['employees']);
      }
      if (!this.employeeRol.pago && !this.employeeRol.tpv) {
        this.router.navigate(['employees/panel']);
        return;
      }
      if (!this.idRestaurant) {
        this.router.navigate(['']);
      }
    }
  }

  @HostListener('window:resize', ['$event'])
  onResize(event: any) {
    this.checkScreenWidth();
  }

  checkScreenWidth() {
    this.isMobile = window.innerWidth <= 768;
  }

  ionViewWillEnter() {
    this.loadOrderFromLocalStorage();
    this.loadOrderLines();
  }

  onKeyPress(key: string): void {
    if (key === ',' && this.pay.includes(',')) return;
    this.pay += key; // Emitimos la tecla presionada hacia el componente padre
  }

  loadOrderFromLocalStorage() {
    const storedOrder = localStorage.getItem('order');
    if (storedOrder) {
      const plain = JSON.parse(storedOrder);
      this.order = Order.fromJSON(plain);
      this.idPedido = this.order.id;
      this.guests = this.order.comensales!;
      this.tableName = localStorage.getItem('tableName')!;
    }
  }

  setEditingLine(orderLine: OrderLine): void {
    this.currentEditingLine = orderLine;
    this.updateCurrentInputValue();
  }

  setEditingMode(mode: 'cantidad' | 'total'): void {
    this.editingMode = mode;
    this.updateCurrentInputValue();
  }

  updateCurrentInputValue(): void {
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

  // Teclado numérico
  onKeyPressed(key: string): void {
    if (!this.currentEditingLine) return;

    if (key === '←') {
      this.currentInputValue = this.currentInputValue.slice(0, -1) || '0';
    } else {
      this.currentInputValue =
        this.currentInputValue === '0' ? key : this.currentInputValue + key;
    }

    const parsedValue = parseFloat(this.currentInputValue.replace(',', '.'));
    if (isNaN(parsedValue)) return;

    if (this.editingMode === 'cantidad') {
      this.currentEditingLine.cantidad = parsedValue;
    } else if (
      this.currentEditingLine.cantidad &&
      this.currentEditingLine.cantidad > 0
    ) {
      this.currentEditingLine.precio =
        parsedValue / this.currentEditingLine.cantidad;
    }

    this.saveLineWithDebounce(this.currentEditingLine);
  }

  saveLineWithDebounce(orderLine: OrderLine) {
    const lineId = orderLine.id;
    if (!lineId) return;

    if (this.debounceMap.has(lineId)) {
      clearTimeout(this.debounceMap.get(lineId));
    }

    this.debounceMap.set(
      lineId,
      setTimeout(() => {
        this.orderLineService.putOrderLines(orderLine).subscribe({
          error: (err) => console.error('Error al guardar línea:', err),
        });
      }, 500)
    );
  }

  loadOrderLines() {
    this.orderLineService.getListOrderLines(this.idPedido!).subscribe({
      next: (data) => {
        this.orderLines = data;
        this.total = this.orderLines.reduce(
          (acc, curr) => acc + (curr.precio ?? 0) * (curr.cantidad ?? 0),
          0
        );
        this.restante = this.total;
      },
      error: (err) =>
        this.alertService.show(
          'Error',
          'Error al cargar los datos del pedido.',
          'error'
        ),
    });
  }

  printTicket(): void {
    if (!this.idPedido) return;

    const iva = (this.total * 10) / (100 + 10);
    const idRestaurante = 1;

    this.ticketService
      .generateTicket(this.idPedido, this.total, iva, idRestaurante)
      .subscribe({
        next: (response) => {
          const url = response.ticket_url;
          const win = window.open(url, '_blank');
          if (win) win.focus();
        },
        error: (err) =>
          this.alertService.show(
            'Error',
            'Error al generar el ticket.',
            'error'
          ),
      });
  }

  handleValidated() {
    let valuePay = 0;
    if (this.restante <= 0) {
      for (let payment of this.payments) {
        valuePay += Number(payment.cantidad);
        if (valuePay > this.total) {
          payment.cantidad = Number(payment.cantidad) + this.restante;
        }
        this.validatePayments(payment);
      }
      this.completeOrder();
      if(this.idRestaurant){
        this.router.navigate(['restaurant/tables']);
      }
      if(this.idCompany){
        this.router.navigate(['order-admin']);
      }
    }
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
      },
      error: (err) =>
        this.alertService.show(
          'Error',
          'Error al actualizar los comensales.',
          'error'
        ),
    });
    this.showGuestsModal = false;
  }

  openPinModal() {
    this.showPinModal = true;
  }

  closePinModal() {
    this.showPinModal = false;
  }

  openPayModal(tipo: string) {
    this.tipo = tipo;
    this.showPayModal = true;
  }
  closePayModal() {
    this.showPayModal = false;
  }

  openClientModal() {
    this.showClientModal = true;
    this.addingNewClient = false;
    this.clientSearchCif = '';
    this.foundClient = null;
  }

  onCifInputChange() {
    const numericCif = this.clientSearchCif.trim();
    this.clientSearchCif = numericCif;

    if (numericCif.length == 9) {
      this.clientService.findClientByCif(numericCif).subscribe({
        next: (client) => {
          if (client) {
            this.foundClient = client; // Mostrar cliente encontrado
          } else {
            console.warn('No se encontró cliente con ese CIF');
            this.foundClient = null; // Restablecer si no se encuentra
          }
        },
        error: (err) => {
          console.error('Error al obtener cliente:', err);
        },
      });
    } else {
      this.cifLength = false;
    }
  }

  onCifKeyPressed(key: string): void {
    if (key === '←') {
      this.clientSearchCif = this.clientSearchCif.slice(0, -1);
    } else {
      this.clientSearchCif = this.clientSearchCif + key;
    }
  }

  saveNewClient() {
    this.newClient = this.clientForm.value;
    if (this.newClient) {
      if (!this.newClient.razonSocial || !this.newClient.cif) {
        alert('Rellena nombre y CIF');
        return;
      }
      this.restaurantService.getRestaurant(this.idRestaurant).subscribe({
        next: (data) => {
          this.clientService
            .addClient(this.newClient!, data.idEmpresa!)
            .subscribe({
              next: (client) => {
                this.printBill(client);
                this.showClientModal = false;
              },
              error: (err) =>
                this.alertService.show(
                  'Error',
                  'Error al guardar el cliente.',
                  'error'
                ),
            });
        },
        error: (e) =>
          this.alertService.show(
            'Error',
            'Error al obtener el restaurante.',
            'error'
          ),
      });
    }
  }

  appendDigit(digit: string) {
    if (this.pin.length < 4) {
      this.pin += digit;
      this.pinLength += '*';
    }
  }
  onPayKeyPress(key: string) {
    if (key === '←') {
      this.deletePay();
    } else {
      this.onKeyPress(key);
    }
  }

  deleteDigit() {
    this.pin = this.pin.slice(0, -1);
    this.pinLength = this.pinLength.slice(0, -1);
  }

  deletePay() {
    this.pay = this.pay.slice(0, -1);
  }

  printBill(client: Client): void {
    if (!this.idPedido) return;
    const idClient = client.id;
    const iva = (this.total * 10) / (100 + 10);
    const idRestaurante = 1;
    const metodo: string = 'Tarjeta';

    this.ticketService
      .generateBill(
        this.idPedido,
        this.total,
        iva,
        idRestaurante,
        idClient!,
        metodo
      )
      .subscribe({
        next: (response) => {
          const url = response.ticket_url;
          const win = window.open(url, '_blank');
          if (win) win.focus();
        },
        error: (err) =>
          this.alertService.show(
            'Error',
            'Error al generar la factura.',
            'error'
          ),
      });
  }

  addPayment(): void {
    if (!this.idPedido) return;
    const payment = new Payment({
      tipo: this.tipo,
      cantidad: Number(parseFloat(this.pay.replace(',', '.'))),
      fecha: undefined,
      idPedido: this.idPedido,
      idCliente: undefined,
      razonSocial: undefined,
      cif: undefined,
      nFactura: undefined,
      correo: undefined,
      direccionFiscal: undefined,
    });

    this.payments.push(payment);
    this.pay = '';
    this.restante -= payment.cantidad!;
  }

  completeOrder() {
    this.orderService.putOrderStatus(this.idPedido!, 'Cerrado').subscribe({});
  }

  cancelOrder() {
    this.orderService.putOrderStatus(this.idPedido!, 'Cancelado').subscribe({
      next: (response) => {
        if(this.idRestaurant){
          this.router.navigate(['restaurant/tables']);
        }
        if(this.idCompany){
          this.router.navigate(['order-admin']);
        }
      },
      error: (err) =>
        this.alertService.show(
          'Error',
          'Error al cancelar el pedido.',
          'error'
        ),
    });
  }

  validatePayments(payment: Payment): void {
    this.paymentService.addPayment(payment).subscribe({
      next: (response) => {
        this.pay = '';
      },
    });
  }

  back() {
    this.router.navigate(['/tpv']);
  }
  cancel() {
    this.addingNewClient = false;
    this.clientForm.reset();
  }
  abs(value: string): number {
    return Math.abs(Number(value));
  }

  deletePayment(tipo: string, cantidad: number) {
    const item = this.payments.find(
      (item) => item.tipo === tipo && item.cantidad === cantidad
    );

    if (item) {
      const index = this.payments.indexOf(item);

      if (index !== -1) {
        this.payments.splice(index, 1);
        this.restante += cantidad;
      }
    }
  }
}

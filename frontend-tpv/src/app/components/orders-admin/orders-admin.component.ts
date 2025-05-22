import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import {
  IonContent,
  IonGrid,
  IonRow,
  IonCol,
  IonLabel,
} from '@ionic/angular/standalone';
import { HeaderTpvComponent } from '../header-tpv/header-tpv.component';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { EmployeeService } from 'src/app/services/employee.service';
import { Employee } from 'src/app/models/Employee';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { RoleService } from 'src/app/services/role.service';
import { Role } from 'src/app/models/Role';
import { OrderService } from 'src/app/services/order.service';
import { Order } from 'src/app/models/Order';
import { OrderLineService } from 'src/app/services/order-line.service';
import { OrderLine } from 'src/app/models/OrderLine';
import { Payment } from 'src/app/models/Payment';
import { PaymentService } from 'src/app/services/payment.service';
import { TicketService } from 'src/app/services/ticket.service';
import { ClientService } from 'src/app/services/client.service';
import { Client } from 'src/app/models/Client';
import { TableService } from 'src/app/services/table.service';
import { Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert.service';
@Component({
  selector: 'app-orders-admin',
  templateUrl: './orders-admin.component.html',
  styleUrls: ['./orders-admin.component.scss'],
  imports: [IonicModule, CommonModule, ReactiveFormsModule],
})
export class OrdersAdminComponent implements OnInit {
  employeeRol!: EmployeeRol;
  idCompany!: number;
  idRestaurant!: number;
  orderInfo: OrderLine[] = [];
  listOrders: Order[] = [];
  employee!: Employee;
  showOrderModal: boolean = false;
  filterOrder: Order[] = [];
  filterStates = {
    date: 'none' as 'none' | 'asc' | 'desc',
    status: 'none' as 'none' | 'asc' | 'desc',
  };
  factura: boolean = false;
  originalOrders: Order[] = [];
  infoPayment: Payment[] = [];
  nFactura: string = '';
  idPedido: number = 0;

  findClientModal: boolean = false;
  clients: Client[] = [];
  total: number = 0;
  estado: string = '';
  pedidoModal!: Order;

  constructor(
    private os: OrderService,
    private ols: OrderLineService,
    private p: PaymentService,
    private ts: TicketService,
    private c: ClientService,
    private tableService: TableService,
    private router: Router,
    private alertService: AlertService
  ) {}

  ngOnInit() {
    this.filterOrder = [];
    this.factura = false;
    const storedRestaurant = localStorage.getItem('idRestaurant');
    const storedCompany = localStorage.getItem('idCompany');
    if (storedRestaurant) {
      this.idRestaurant = Number(storedRestaurant);
      this.listOfOrdersByRestaurant();
    }
    const storedEmployeeRol = localStorage.getItem('employeeRol');
    if (storedEmployeeRol) {
      const plain = JSON.parse(storedEmployeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
    }
    if (storedCompany) {
      this.idCompany = Number(storedCompany);
      this.listOfOrders();
    }

    if (!this.idCompany || this.idRestaurant) {
      if (!this.employeeRol) {
        this.router.navigate(['employees']);
      }
      if (!this.employeeRol.pago) {
        this.router.navigate(['employees/panel']);
        return;
      }
      if (!this.idRestaurant) {
        this.router.navigate(['']);
      }
    }
  }

  ionViewWillEnter() {
    this.filterOrder = [];
    this.factura = false;
    const storedRestaurant = localStorage.getItem('idRestaurant');

    if (storedRestaurant) {
      this.idRestaurant = Number(storedRestaurant);
      this.listOfOrdersByRestaurant();
    }
    const storedEmployeeRol = localStorage.getItem('employeeRol');
    if (storedEmployeeRol) {
      const plain = JSON.parse(storedEmployeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
    }

    const storedCompany = localStorage.getItem('idCompany');

    if (storedCompany) {
      this.idCompany = Number(storedCompany);
      this.listOfOrders();
    }
  }

  listOfOrdersByRestaurant() {
    this.os.getOrdersByRestaurant(this.idRestaurant!).subscribe({
      next: (data) => {
        this.listOrders = data;
        this.filterOrder = this.listOrders;
      },
      error: (err) => console.error('❌ Error al cargar oedidos:', err),
    });
  }

  listOfOrders() {
    this.os.getOrdersByCompany(this.idCompany!).subscribe({
      next: (data) => {
        this.listOrders = data;
        this.filterOrder = this.listOrders;
      },
      error: (err) => console.error('❌ Error al cargar pedidos:', err),
    });
  }

  showCreate() {}

  cancel() {
    this.showOrderModal = false;
    this.total = 0;
  }

  filterByStatus(): void {
    this.originalOrders = [...this.filterOrder];
    if (this.filterStates.status === 'none') {
      this.filterOrder.sort((a, b) => a.estado!.localeCompare(b.estado!));
      this.filterStates.status = 'asc';
    } else if (this.filterStates.status === 'asc') {
      this.filterOrder.sort((a, b) => b.estado!.localeCompare(a.estado!));
      this.filterStates.status = 'desc';
    } else {
      this.filterOrder = [...this.originalOrders];
      this.filterStates.status = 'none';
    }

    // Resetear otros filtros si es necesario
    this.filterStates.date = 'none';
  }

  filterByDate(): void {
    this.originalOrders = [...this.filterOrder];

    if (this.filterStates.date === 'none') {
      this.filterOrder.sort(
        (a, b) =>
          new Date(a.fechaInicio!).getTime() -
          new Date(b.fechaInicio!).getTime()
      );
      this.filterStates.date = 'asc';
    } else if (this.filterStates.date === 'asc') {
      this.filterOrder.sort(
        (a, b) =>
          new Date(b.fechaInicio!).getTime() -
          new Date(a.fechaInicio!).getTime()
      );
      this.filterStates.date = 'desc';
    } else {
      this.filterOrder = [...this.originalOrders];
      this.filterStates.date = 'none';
    }

    // Resetear otros filtros si no quieres combinar
    this.filterStates.status = 'none';
  }
  filterByDateEnd(): void {
    this.originalOrders = [...this.filterOrder];

    const sortByDateEnd = (asc: boolean) => {
      this.filterOrder.sort((a, b) => {
        const fechaA = a.fechaFin ? new Date(a.fechaFin).getTime() : null;
        const fechaB = b.fechaFin ? new Date(b.fechaFin).getTime() : null;

        if (fechaA === null && fechaB === null) return 0;
        if (fechaA === null) return 1; // a sin fecha, va después
        if (fechaB === null) return -1; // b sin fecha, va después

        return asc ? fechaA - fechaB : fechaB - fechaA;
      });
    };

    if (this.filterStates.date === 'none') {
      sortByDateEnd(true);
      this.filterStates.date = 'asc';
    } else if (this.filterStates.date === 'asc') {
      sortByDateEnd(false);
      this.filterStates.date = 'desc';
    } else {
      this.filterOrder = [...this.originalOrders];
      this.filterStates.date = 'none';
    }

    this.filterStates.status = 'none';
  }

  loadInfo(pedido: Order) {
    this.showOrderModal = true;
    this.infoPayment = [];
    this.total = 0;
    this.estado = pedido.estado!;
    this.infoPayment = [];
    this.pedidoModal = pedido;
    this.ols.getListOrderLines(pedido.id!).subscribe({
      next: (data) => {
        this.orderInfo = data;
        this.idPedido = this.orderInfo[0].idPedido!;
      },
      error: (err) =>
        this.alertService.show(
          'Error',
          'Error al cargar los datos del pedido.',
          'error'
        ),
    });
    if (pedido.estado === 'Cerrado') {
      this.findPayment(pedido.id!);
    }
  }

  findPayment(idPedido: number) {
    console.log('Estoy en el findPayment');
    this.p.findPayment(idPedido).subscribe({
      next: (data) => {
        this.infoPayment = data;
        this.infoPayment.forEach((pay) => {
          this.total += pay.cantidad!;
        });
        if (this.infoPayment[0].nFactura) {
          this.factura = true;
          this.nFactura = this.infoPayment[0].nFactura.replace('/', '-');
        } else {
          this.factura = false;
          this.nFactura = '';
        }
      },
    });
  }

  showBill(): void {
    console.log('Estoy en el showBill');
    console.log(this.nFactura);
    this.ts.getBill(this.nFactura).subscribe({
      next: (response) => {
        if (response.url) {
          // Verifica si la URL ya es absoluta
          const finalUrl = response.url.startsWith('http')
            ? response.url
            : `http://localhost${response.url}`;

          window.open(finalUrl, '_blank');
        }
      },
      error: (err) => {
        this.alertService.show('Error', 'Error al cargar factura', 'error');
      },
    });
  }

  public results = [...this.clients];
  createBill() {
    this.findClientModal = true;
    this.showOrderModal = false;
    //let idEmpresa = this.employeeRol.idEmpresa;
    this.c
      .getAllClientsCompany(
        this.idCompany ? this.idCompany! : this.employeeRol.idEmpresa!
      )
      .subscribe({
        next: (data) => {
          this.clients = data;
          this.results = this.clients;
        },
        error: (err) =>
          this.alertService.show(
            'Error',
            'Error al generar la factura.',
            'error'
          ),
      });
  }

  handleInput(event: Event) {
    const target = event.target as HTMLIonSearchbarElement;
    const query = target.value?.toLowerCase() || '';
    this.results = this.clients.filter((d) =>
      d.razonSocial?.toLocaleLowerCase().includes(query)
    );
  }

  doBill(client: Client) {
    const iva = (this.total * 10) / (100 + 10);
    this.ts
      .updateBill(
        this.infoPayment[0].idPedido!,
        this.total,
        iva,
        this.idRestaurant,
        client.id!,
        'tarjeta'
      )
      .subscribe({
        next: (data) => {
          console.log('DOBILL');
          console.log(data);
          this.findClientModal = false;
          const match = data.ticket_url.match(/bill_(.+)\.pdf/);
          this.nFactura = match ? match[1] : "";
          this.showBill();
        },
        error: (err) =>
          this.alertService.show(
            'Error',
            'Error al crear la factura.',
            'error'
          ),
      });
  }
  pay() {
    this.tableService.findByIdTable(this.pedidoModal.idMesa!).subscribe({
      next: (data) => {
        this.findClientModal = false;
        this.showOrderModal = false;
        localStorage.setItem('order', JSON.stringify(this.pedidoModal));
        localStorage.setItem('tableName', data.mesa!);
        setTimeout(() => {
          this.router.navigate(['/payment']);
        }, 0);
      },
    });
  }
}

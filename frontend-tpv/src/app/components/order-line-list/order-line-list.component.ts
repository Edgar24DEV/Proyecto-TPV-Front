import { Component, Input, OnInit, OnChanges, SimpleChanges } from '@angular/core';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { OrderLineService } from 'src/app/services/order-line.service';
import { OrderLine } from 'src/app/models/OrderLine';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Order } from 'src/app/models/Order';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'app-order-line-list',
  templateUrl: './order-line-list.component.html',
  styleUrls: ['./order-line-list.component.scss'],
  imports: [IonicModule, CommonModule, FormsModule, ReactiveFormsModule],
  standalone: true,
})
export class OrderLineListComponent implements OnInit, OnChanges {
  @Input() showTotals: boolean = true;
  @Input() orderLines: OrderLine[] = [];
  @Input() setEditingLine!: (orderLine: any) => void;
  @Input() setEditingMode!: (mode: 'cantidad' | 'total') => void;
  @Input() currentInputValue: string = '';
  @Input() editingLine?: OrderLine;
  @Input() editingMode: 'cantidad' | 'total' = 'cantidad';
  @Input() currentOrderId?: number;

  idRestaurant!: number;
  idPedido?: number = 0;
  order!: Order;
  orderLineForm: FormGroup;
  total: number = 0;
  impuestos: number = 0;
  iva: number = 10;

  orderLineToDelete: OrderLine | null = null;
  showDeleteModal: boolean = false;

  constructor(
    private orderLineService: OrderLineService,
    private router: Router,
    private formBuilder: FormBuilder,
    private alertService: AlertService
  ) {
    this.orderLineForm = this.formBuilder.group({
      id: [''],
      cantidad: [''],
      precio: [''],
      nombre: [''],
    });
  }

  ngOnInit() {
    const storedCompany = localStorage.getItem('idCompany');
    const storedRestaurantId = localStorage.getItem('idRestaurant');
    if (!storedRestaurantId && !storedCompany) {
        this.router.navigate(['/loginRestaurant']);
    }
    // No necesitas leer el order de localStorage aquí para obtener el idPedido

    this.idRestaurant = Number(storedRestaurantId);
    this.loadInitialOrderLines(); // Cargar las líneas iniciales usando el Input si está disponible
}

loadInitialOrderLines(): void {
    if (this.currentOrderId) {
        this.idPedido = this.currentOrderId;
        this.listOfOrderLines();
    }
}
  

ngOnChanges(changes: SimpleChanges): void {
  if (changes['orderLines']) {
      this.recalcularTotales();
  }
  if (changes['currentOrderId'] && changes['currentOrderId'].currentValue) {
      this.idPedido = changes['currentOrderId'].currentValue;
      this.listOfOrderLines(); // Recargar las líneas cuando cambia el id del pedido
  }
}

  recalcularTotales(): void {
    this.total = this.orderLines.reduce<number>((acc, curr) => {
      return acc + (curr.precio! * curr.cantidad!);
    }, 0);
    this.impuestos = (this.total * this.iva) / (100 + this.iva);
  }

  isEditing(orderLine: OrderLine, mode: 'cantidad' | 'total'): boolean {
    return this.editingLine?.id === orderLine.id && this.editingMode === mode;
  }

  onCantidadChange(orderLine: OrderLine): void {
    this.orderLineService.putOrderLines(orderLine).subscribe({
      next: () => {
        this.listOfOrderLines();
      },
      error: (err) => {
        
      },
    });
  }

  onTotalChange(event: any, orderLine: OrderLine): void {
    const rawValue = typeof event === 'string' ? event : event?.target?.value || event?.value || '';
    const total = parseFloat(rawValue.replace(',', '.'));
    if (orderLine.cantidad && orderLine.cantidad > 0 && !isNaN(total)) {
      orderLine.precio = total / orderLine.cantidad;
      this.orderLineService.putOrderLines(orderLine).subscribe({
        next: () => {
          this.listOfOrderLines();
        },
        error: (err) => {
         
        },
      });
    }
  }

  selectOrderLine(orderLine: OrderLine): void {
    this.setEditingLine(orderLine);
  }

  confirmDelete(orderLine: OrderLine): void {
    this.orderLineToDelete = orderLine;
    this.showDeleteModal = true;
  }

  deleteOrderLine(id?: number): void {
    if (!this.orderLineToDelete) return;

    this.orderLineService.deleteOrderLine(id!).subscribe({
      next: () => {
        this.showDeleteModal = false;
        this.orderLineToDelete = null;
        this.listOfOrderLines();
      },
      error: (err) => {
        this.alertService.show('Error', 'Error al eliminar línea de pedido.', 'error');
      },
    });
  }

  listOfOrderLines(): void {
    if (!this.idPedido) return;

    this.orderLineService.getListOrderLines(this.idPedido).subscribe({
      next: (data) => {
        this.orderLines = data;
        this.recalcularTotales();
      },
      error: (err) => {
      },
    });
  }

  // Nuevo método refreshLines
  refreshLines(): void {
    this.listOfOrderLines();
  }
}

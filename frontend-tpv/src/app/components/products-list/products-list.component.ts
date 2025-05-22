import {
  Component,
  EventEmitter,
  Input,
  OnChanges,
  OnInit,
  Output,
} from '@angular/core';

import { Router } from '@angular/router';

import { TableService } from 'src/app/services/table.service';

import {
  IonTitle,
  IonCard,
  IonToolbar,
  IonCardHeader,
  IonCol,
  IonHeader,
} from '@ionic/angular/standalone';

import { CommonModule } from '@angular/common';

import { IonicModule } from '@ionic/angular';

import { ProductService } from 'src/app/services/product.service';

import { Product } from 'src/app/models/Product';

import { OrderLine } from 'src/app/models/OrderLine';

import { OrderLineService } from 'src/app/services/order-line.service';

import { Order } from 'src/app/models/Order';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'app-products-list',

  templateUrl: './products-list.component.html',

  styleUrls: ['./products-list.component.scss'],

  imports: [IonicModule, CommonModule],
})
export class ProductsListComponent implements OnInit, OnChanges {
  @Output() envioProductos = new EventEmitter<void>();

  @Input() currentOrderId?: number;

  products: Product[] = [];

  idRestaurant!: number;

  filteredProducts: Product[] = [];

  idPedido?: number = 0;

  order!: Order;
  defaultImage: string = 'assets/defect-image.png';

  constructor(
    private productService: ProductService,

    private router: Router,

    private orderLineService: OrderLineService,
    private alertService: AlertService
  ) {}

  ngOnInit() {
    const storedRestaurantId = localStorage.getItem('idRestaurant');

    if (!storedRestaurantId) {
      this.router.navigate(['/loginRestaurant']);
    }

    const storedOrder = localStorage.getItem('order');

    if (storedOrder) {
      const plain = JSON.parse(storedOrder);

      this.order = Order.fromJSON(plain);

      this.idPedido = this.order.id;

    }

    this.idRestaurant = Number(storedRestaurantId);

    this.listOfProducts();
  }

  listOfProducts() {
    this.productService.getListProductsRestaurant(this.idRestaurant).subscribe({
      next: (data) => {
        this.products = data.filter(element => element.activo == true);
        this.filteredProducts = this.products;
      },
      error: (err) => {
        // Alerta al usuario sobre el error
        alert('Error al cargar los productos. Inténtalo nuevamente.');
      },
    });
  }

  @Input() categoryId?: number;

  ngOnChanges() {
    if (this.categoryId === undefined) {
      this.filteredProducts = this.products; // ← mostrar todos
    } else {
      this.filterProductsByCategory(this.categoryId);
    }
  }

  filterProductsByCategory(categoryId: number) {
    this.filteredProducts = this.products.filter(
      (p) => p.idCategoria === categoryId
    );
  }

  addProduct(product: Product) {
    let orderLine: OrderLine;
    orderLine = {
      id: -1,
      idPedido: this.currentOrderId, // <---- USAR currentOrderId AQUÍ
      idProducto: product.id,
      cantidad: 1,
      precio: product.precio,
      nombre: product.nombre,
      observaciones: ' ',
      estado: 'Pendiente',
    };

    this.orderLineService.postOrderLines(orderLine).subscribe({
      next: (res) => {
        this.envioProductos.emit()
      },
      error: (err) => this.alertService.show('Error', 'Error al añadir el producto en el pedido.', 'error')
    });
  }
}

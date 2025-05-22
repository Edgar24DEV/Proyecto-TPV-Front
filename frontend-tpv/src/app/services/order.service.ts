import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Order } from '../models/Order';

@Injectable({
  providedIn: 'root',
})
export class OrderService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getOrderTable(id: number): Observable<Order> {
    return this.http
      .get<any>(`${this.apiUrl}/order/get-order`, {
        params: { id_mesa: id.toString() },
      })
      .pipe(
        map((response) => {
          const item = response?.data ?? response;

          return new Order({
            id: item.id,
            estado: item.estado,
            fechaInicio: item.fecha_inicio,
            fechaFin: item.fecha_fin,
            comensales: item.comensales,
            idMesa: item.id_mesa,
          });
        })
      );
  }

  postOrder(order: Order): Observable<Order> {
    return this.http.post<any>(`${this.apiUrl}/order`, {
      comensales: order.comensales,
      idMesa: order.idMesa,
    });
  }

  putOrderDiners(idPedido: number, nComensales: number): Observable<Order> {
    return this.http.put<Order>(`${this.apiUrl}/order`, {
      id: idPedido.toString(),
      comensales: nComensales.toString(),
    });
  }

  putOrderStatus(idPedido: number, estado: string): Observable<Order> {
    return this.http.put<Order>(`${this.apiUrl}/order-status`, {
      id: idPedido.toString(),
      estado: estado.toString(),
    });
  }

  getOrdersByRestaurant(idRestaurant: number): Observable<Order[]> {
    return this.http
      .get<Order[]>(`${this.apiUrl}/restaurant/orders`, {
        params: { id_restaurante: idRestaurant.toString() },
      })
      .pipe(map((orders) => orders.map((order) => Order.fromJSON(order))));
  }

  getOrdersByCompany(idCompany: number): Observable<Order[]> {
    return this.http
      .get<Order[]>(`${this.apiUrl}/company/orders`, {
        params: { id_empresa: idCompany.toString() },
      })
      .pipe(map((orders) => orders.map((order) => Order.fromJSON(order))));
  }
}

import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Product } from '../models/Product';
import { OrderLine } from '../models/OrderLine';

@Injectable({
  providedIn: 'root',
})
export class OrderLineService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getListOrderLines(id: number): Observable<OrderLine[]> {
    return this.http
      .get<any>(`${this.apiUrl}/orders`, {
        params: { id_pedido: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }

          return rawData.map(
            (tempOrderLine) =>
              new OrderLine({
                id: tempOrderLine.id,
                idPedido: tempOrderLine.idPedido,
                idProducto: tempOrderLine.idProducto,
                cantidad: tempOrderLine.cantidad,
                precio: tempOrderLine.precio,
                nombre: tempOrderLine.nombre,
                observaciones: tempOrderLine.observaciones,
                estado: tempOrderLine.estado,
              })
          );
        })
      );
  }

  putOrderLines(orderLine: OrderLine): Observable<OrderLine> {
    return this.http
      .put<OrderLine>(`${this.apiUrl}/order-line`, {
         id: orderLine.id?.toString(), cantidad: orderLine.cantidad, precio: orderLine.precio?.toFixed(2), nombre: orderLine.nombre,
      })
  }

  postOrderLines(orderLine: OrderLine): Observable<OrderLine> {
    return this.http
      .post<any>(`${this.apiUrl}/order-line`, {
         id_pedido: orderLine.idPedido,
         id_producto: orderLine.idProducto,
         cantidad: orderLine.cantidad ?? 1,
         observaciones: orderLine.observaciones,
         estado: "Pendiente",
      })
  }

  deleteOrderLine(id: number): Observable<OrderLine> {
    return this.http.request<OrderLine>('DELETE', `${this.apiUrl}/order-line`, {
      body: { id }
    });
  }


}

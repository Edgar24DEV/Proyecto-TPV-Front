import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root',
})
export class TicketService {
  constructor(private http: HttpClient) {}

  private apiUrl = environment.apiUrl;

  generateTicket(
    idPedido: number,
    total: number,
    iva: number,
    idRestaurante: number
  ): Observable<{ ticket_url: string }> {
    const body = {
      id_pedido: idPedido,
      id_restaurante: idRestaurante,
      total,
      iva,
    };

    return this.http.post<{ ticket_url: string }>(
      `${this.apiUrl}/generate-ticket`,
      body
    );
  }

  generateBill(
    idPedido: number,
    total: number,
    iva: number,
    idRestaurante: number,
    idCliente: number,
    tipo: string
  ): Observable<{ ticket_url: string }> {
    const body = {
      id_pedido: idPedido,
      id_restaurante: idRestaurante,
      id_cliente: idCliente,
      total,
      iva,
      tipo,
    };
    return this.http.post<{ ticket_url: string }>(
      `${this.apiUrl}/generate-bill`,
      body
    );
  }

  updateBill(
    idPedido: number,
    total: number,
    iva: number,
    idRestaurante: number,
    idCliente: number,
    tipo: string
  ): Observable<{ ticket_url: string }> {
    const body = {
      id_pedido: idPedido,
      id_restaurante: idRestaurante,
      id_cliente: idCliente,
      total,
      iva,
      tipo,
    };
    console.warn("Estoy en el servicio");
    console.log(body);
    return this.http.put<{ ticket_url: string }>(
      `${this.apiUrl}/update-bill`,
      body
    );
  }

  getBill(nFactura: string) {
    return this.http.get<{ url: string }>(`${this.apiUrl}/show-bill`, {
      params: { n_factura: nFactura } // Envía el parámetro como query string
    });
  }
}

import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Payment } from '../models/Payment';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { map } from 'rxjs/operators';
import { Client } from '../models/Client';

@Injectable({
  providedIn: 'root',
})
export class PaymentService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  addPayment(payment: Payment): Observable<any> {
    const payload = this.mapToPayload(payment);
    return this.http.post<any>(`${this.apiUrl}/payment`, payload);
  }

  private mapToPayload(payment: Payment): any {
    return {
      tipo: payment.tipo,
      cantidad: payment.cantidad,
      fecha: null,
      id_pedido: payment.idPedido,
      id_cliente: null,
      razon_social: null,
      cif: null,
      n_factura: null,
      correo: null,
      direccion_fiscal: null,
    };
  }

  findPayment(id: number): Observable<Payment[]> {
    return this.http
      .get<any>(`${this.apiUrl}/order/payments`, {
        params: { id_pedido: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;
  
          // Si no hay datos o el array está vacío, lanzamos un error
          if (!rawData || !Array.isArray(rawData) || rawData.length === 0) {
            throw new Error('La respuesta del servidor no contiene pagos válidos');
          }
  
          return rawData.map(
            (paymentData) =>
           new Payment({
            id: paymentData.id,
            tipo: paymentData.tipo,
            cantidad: paymentData.cantidad,
            fecha: paymentData.fecha, // Asegúrate de que la propiedad "fecha" esté en la respuesta
            idPedido: paymentData.idPedido,
            idCliente: paymentData.idCliente,
            razonSocial: paymentData.razonSocial,
            cif: paymentData.CIF, // Asegúrate de que la propiedad "CIF" esté en la respuesta
            nFactura: paymentData.nFactura,
            correo: paymentData.correo,
            direccionFiscal: paymentData.direccionFiscal,
          })
        );
      })
    );
  }
  updateClient(client: Client, id: number): Observable<Payment> {
    const body = {
      id: id,  
      idCliente: client.id,
      razonSocial: client.razonSocial,
      CIF: client.cif,
      direccionFiscal: client.direccion,
      correo: client.email,
    };
  
    return this.http.put<Payment>(`${this.apiUrl}/payment`, body);
  }
  
  
  
}

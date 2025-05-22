// src/app/services/client.service.ts

import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map, Observable } from 'rxjs';
import { Client } from '../models/Client';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root',
})
export class ClientService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getListClientsCompany(id: number): Observable<Client[]> {
      return this.http.get<any>(`${this.apiUrl}/clients`, {
        params: { id_empresa: id.toString() }  
      }).pipe(
        map(response => {
    
          const rawData = response?.data ?? response;
    
          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }
    
          return rawData.map(client => new Client({
            id: client.id,
            razonSocial: client.razon_social,
            cif: client.cif,
            direccion: client.direccion_fiscal,
            email: client.email,
          }));
        })
      );
    }

  findClientByCif(cif: string): Observable<Client | null> {
    return this.http
      .get<any>(`${this.apiUrl}/clients/find-by-cif`, {
        params: { cif },
      })
      .pipe(
        map((response) => {
          const item = response?.data ?? response;
          console.log('Respuesta del backend:', response);

          if (!item || !item.id) return null;

          return new Client({
            id: item.id,
            razonSocial: item.razon_social ?? '',
            cif: item.cif ?? '',
            direccion: item.direccion_fiscal ?? '',
            email: item.correo ?? '',
          });
        })
      );
  }

  addClient(client: Client, idEmpresa: number): Observable<Client> {
    return this.http.post<Client>(`${this.apiUrl}/clients`, {
      razon_social: client.razonSocial,
      cif: client.cif,
      direccion_fiscal: client.direccion,
      correo: client.email,
      id_empresa: idEmpresa,
    });
  }

  updateClient(client: Client, idEmpresa: number): Observable<Client> {
    return this.http.put<Client>(`${this.apiUrl}/clients`, {
      id: client.id,
      razon_social: client.razonSocial,
      cif: client.cif,
      direccion_fiscal: client.direccion,
      correo: client.email,
      id_empresa:idEmpresa,
   });
  }

  deleteClient(id: number): Observable<Client> {
    return this.http.delete<any>(`${this.apiUrl}/clients`,{  
      params: { id:id} 
    });
  }

  findByIdClient(id: number): Observable<Client> {
    return this.http.get<any>(`${this.apiUrl}/clients/find-by-id`, {
      params: { id: id.toString() }
    }).pipe(
      map(response => {
        const item = response?.data ?? response;
        return new Client({
          id: item.id,
          razonSocial: item.razon_social,
          cif: item.cif,
          direccion: item.direccion_fiscal,
          email: item.correo
        });
      })
    );
  }

  getAllClientsCompany(id: number): Observable<Client[]> {
    return this.http.get<any[]>(`${this.apiUrl}/clients`, {
      params: { id_empresa: id.toString() },
    }).pipe(
      map((response) => {
       
        if (!Array.isArray(response)) {
          console.error('La respuesta no es un array de clientes');
          return [];
        }
  
        return response.map(item =>
          new Client({
            id: item.id,
            razonSocial: item.razon_social ?? '',
            cif: item.cif ?? '',
            direccion: item.direccion_fiscal ?? '',
            email: item.correo ?? '',
          })
        );
      })
    );
  }
  
}

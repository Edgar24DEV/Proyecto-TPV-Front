import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Restaurant } from '../models/Restaurant';

@Injectable({
  providedIn: 'root',
})
export class RestaurantService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getRestaurant(id: number): Observable<Restaurant> {
    return this.http
      .get<any>(`${this.apiUrl}/restaurant`, {
        params: { id_restaurante: id.toString() },
      })
      .pipe(
        map((response) => {
          const item = response?.data ?? response;

          return new Restaurant({
            id: item.id,
            razonSocial: item.razonSocial,
            direccion: item.direccion,
            telefono: item.telefono,
            contrasenya: item.contrasenya,
            direccionFiscal: item.direccionFiscal,
            cif: item.cif,
            idEmpresa: item.idEmpresa,
          });
        })
      );
  }
  getListRestaurantCompany(id: number): Observable<Restaurant[]> {
    return this.http
      .get<any>(`${this.apiUrl}/restaurants`, {
        params: { id_empresa: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }

          return rawData.map(
            (item) =>
              new Restaurant({
                id: item.id,
                nombre: item.nombre,
                razonSocial: item.razonSocial,
                direccion: item.direccion,
                telefono: item.telefono,
                contrasenya: item.contrasenya,
                direccionFiscal: item.direccionFiscal,
                cif: item.cif,
                idEmpresa: item.idEmpresa,
              })
          );
        })
      );
  }

  postLoginRestaurant(
    nombre: string,
    contrasenya: string
  ): Observable<Restaurant> {
    return this.http
      .post<any>(`${this.apiUrl}/restaurant/login`, {
        nombre,
        contrasenya,
      })
      .pipe(
        map((response) => {

          const rawData = response?.data ?? response; // usa response.data si existe, sino response directo

          if (!rawData || typeof rawData !== 'object') {
            throw new Error('Respuesta inesperada del servidor');
          }
          return new Restaurant({
            id: rawData.id,
            nombre: rawData.nombre,
            direccion: rawData.direccion,
            telefono: rawData.telefono,
            direccionFiscal: rawData.direccionFiscal,
            cif: rawData.cif,
            razonSocial: rawData.razonSocial,
            idEmpresa: rawData.idEmpresa,
          });
        })
      );
  }
  postRestaurant(
    restaurant: Restaurant,
    idEmpresa: number
  ): Observable<Restaurant> {
    const body = {
      nombre: restaurant.nombre,
      direccion: restaurant.direccion,
      telefono: restaurant.telefono,
      contrasenya: restaurant.contrasenya,
      direccion_fiscal: restaurant.direccionFiscal,
      cif: restaurant.cif,
      razon_social: restaurant.razonSocial,
      id_empresa: idEmpresa,
    };

    return this.http.post<Restaurant>(`${this.apiUrl}/restaurant`, body);
  }

  putRestaurant(
    restaurant: Restaurant,
    idEmpresa: number,
    idRestaurante: number
  ): Observable<Restaurant> {
    const body = {
      id: idRestaurante,
      nombre: restaurant.nombre,
      direccion: restaurant.direccion,
      telefono: restaurant.telefono,
      direccion_fiscal: restaurant.direccionFiscal,
      cif: restaurant.cif,
      razon_social: restaurant.razonSocial,
      id_empresa: idEmpresa,
    };
    return this.http.put<Restaurant>(`${this.apiUrl}/restaurant`, body);
  }

  deleteRestaurant(id: number): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl}/restaurant`, {
      body: { id }
    });
  }
  
}

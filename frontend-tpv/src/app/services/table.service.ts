import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Employee } from '../models/Employee';
import { Table } from '../models/Table';

@Injectable({
  providedIn: 'root',
})
export class TableService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getListTableRestaurant(id: number): Observable<Table[]> {
    return this.http
      .get<any>(`${this.apiUrl}/tables`, {
        params: { id_restaurante: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }

          return rawData.map(
            (tempMesa) =>
              new Table({
                id: tempMesa.id,
                mesa: tempMesa.mesa,
                activo: tempMesa.activo,
                idUbicacion: tempMesa.idUbicacion,
                pos_x: tempMesa.posX, // Usar pos_x
                pos_y: tempMesa.posY, // Usar pos_y
              })
          );
        })
      );
  }

  findByIdTable(id: number): Observable<Table> {
    return this.http
      .get<any>(`${this.apiUrl}/table/find-by-id`, {
        params: { id: id.toString() },
      })
      .pipe(
        map((response) => {
          const item = response?.data ?? response;
          return new Table({
            id: item.id,
            mesa: item.mesa,
            activo: item.activo,
            idUbicacion: item.idUbicacion,
            pos_x: item.posX, // Usar pos_x
            pos_y: item.posY, // Usar pos_y
          });
        })
      );
  }

  addTable(table: Table): Observable<Table> {
    return this.http.post<Table>(`${this.apiUrl}/tables`, {
      mesa: table.mesa,
      id_ubicacion: table.idUbicacion,
      pos_x: table.pos_x, // Enviar pos_x
      pos_y: table.pos_y, // Enviar pos_y
    });
  }

  updateTable(table: Table): Observable<Table> {
    return this.http.put<Table>(`${this.apiUrl}/tables`, {
      id: table.id,
      mesa: table.mesa,
      activo: table.activo ? 1 : 0,
      id_ubicacion: table.idUbicacion,
      pos_x: table.pos_x, // Actualizar pos_x
      pos_y: table.pos_y, // Actualizar pos_y
    });
  }

  // Método para actualizar solo la posición
  updateTableLocation(table: Table): Observable<Table> {
    return this.http.put<Table>(`${this.apiUrl}/tables/update-location`, {
      id: table.id,
      pos_x: table.pos_x, // Actualizar pos_x
      pos_y: table.pos_y, // Actualizar pos_y
    });
  }

  deleteTable(id: number): Observable<Table> {
    return this.http.delete<any>(`${this.apiUrl}/tables`, {
      params: { id: id.toString() },
    });
  }
}

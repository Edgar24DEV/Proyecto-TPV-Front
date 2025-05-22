import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Location } from '../models/Location';

@Injectable({
  providedIn: 'root',
})
export class LocationService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getListLocationRestaurant(id: number): Observable<Location[]> {
    return this.http
      .get<any>(`${this.apiUrl}/locations`, {
        params: { id_restaurante: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }

          return rawData.map(
            (tempLocation) =>
              new Location({
                id: tempLocation.id,
                ubicacion: tempLocation.ubicacion,
                activo: tempLocation.activoStatus,
                idRestaurante: tempLocation.idRestaurante,
              })
          );
        })
      );
  }
  findByIdLocation(id: number): Observable<Location> {
    return this.http
      .get<any>(`${this.apiUrl}/location/find-by-id`, {
        params: { id: id.toString() },
      })
      .pipe(
        map((response) => {
          const item = response?.data ?? response;
          return new Location({
            id: item.id,
            ubicacion: item.ubicacion,
            activo: item.activoStatus,
            idRestaurante: item.idRestaurant,
          });
        })
      );
  }
  addLocation(location: Location): Observable<Location> {
    return this.http.post<Location>(`${this.apiUrl}/location`, {
      ubicacion: location.ubicacion,
      id_restaurante: location.idRestaurante,
    });
  }

  updateLocation(location: Location): Observable<Location> {
    return this.http.put<Location>(`${this.apiUrl}/location`, {
      id: location.id,
      ubicacion: location.ubicacion,
      activo: location.activo ? 1 : 0,
      id_restaurante: location.idRestaurante,
    });
  }

  deleteLocation(id: number): Observable<Location> {
    return this.http.delete<any>(`${this.apiUrl}/location`, {
      params: { id: id },
    });
  }
}

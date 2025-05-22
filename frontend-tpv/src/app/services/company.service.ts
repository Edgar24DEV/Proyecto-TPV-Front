import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Company } from '../models/Company';

@Injectable({
  providedIn: 'root'
})
export class CompanyService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}
  postLoginCompany(nombre: string, contrasenya: string): Observable<Company> {
    return this.http.post<any>(`${this.apiUrl}/company/login`, {
      nombre,
      contrasenya
    }).pipe(
      map(response => {
        console.log('Respuesta del backend:', response);
  
        const rawData = response?.data ?? response; // usa response.data si existe, sino response directo
  
        if (!rawData || typeof rawData !== 'object') {
          throw new Error('Respuesta inesperada del servidor');
        }
  
        return new Company({
          id: rawData.id,
          nombre: rawData.nombre ? rawData.nombre.trim() : '',
          direccionFiscal: rawData.direccionFiscal,
          cif: rawData.CIF,
          razonSocial: rawData.razonSocial,
          telefono: rawData.telefono,
          correo: rawData.correo,
          contrasenya: rawData.contrasenya
        });
      })
    );
  }

  
  
}

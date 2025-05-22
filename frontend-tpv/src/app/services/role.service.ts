import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map, Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Role } from '../models/Role';

@Injectable({
  providedIn: 'root'
})
export class RoleService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

    getRole(id: number): Observable<Role> {
      return this.http.get<any>(`${this.apiUrl}/role`, {
        params: { id: id.toString() }
      }).pipe(
        map(response => {
          const item = response?.data ?? response;
    
          return new Role({
            id: item.id,
            rol: item.rol,
            productos: item.productos,
            categorias: item.categorias,
            tpv: item.tpv,
            usuarios: item.usuarios,
            mesas: item.mesas,
            restaurante: item.restaurante,
            clientes: item.clientes,
            empresa: item.empresa,
            pago: item.pago,
            idEmpresa: item.id_empresa
          });
          
        })
      );
    }

    getRolesCompany(id: number): Observable<Role[]> {
      return this.http.get<any>(`${this.apiUrl}/roles`, {
        params: { id_empresa: id.toString() }
      }).pipe(
        map(response => {
          const rawData = response?.data ?? response;

          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }
    
          return rawData.map(item => new Role({
            id: item.id,
            rol: item.rol,
            productos: item.productos,
            categorias: item.categorias,
            tpv: item.tpv,
            usuarios: item.usuarios,
            mesas: item.mesas,
            restaurante: item.restaurante,
            clientes: item.clientes,
            empresa: item.empresa,
            pago: item.pago,
            idEmpresa: item.id_empresa
          })
        )
        })
      );
    }
    addRole(role: Role, idCompany: number): Observable<Role> {
      console.log("Estoy en el servicio" ,role);
      return this.http.post<Role>(`${this.apiUrl}/role`, {
        rol: role.rol,
        productos: role.productos ? 1 : 0,
        categorias: role.categorias ? 1 : 0,
        tpv: role.tpv ? 1 : 0,
        usuarios: role.usuarios ? 1 : 0,
        mesas: role.mesas ? 1 : 0,
        restaurante: role.restaurante ? 1 : 0,
        clientes: role.clientes ? 1 : 0,
        empresa: role.empresa ? 1 : 0,
        pago: role.pago ? 1 : 0,
        id_empresa: idCompany
      });
    }
    
    updateRole(role: Role, idCompany: number): Observable<Role> {
      return this.http.put<Role>(`${this.apiUrl}/role`, {
        id: role.id,
        rol: role.rol,
        productos: role.productos ? 1 : 0,
        categorias: role.categorias ? 1 : 0,
        tpv: role.tpv ? 1 : 0,
        usuarios: role.usuarios ? 1 : 0,
        mesas: role.mesas ? 1 : 0,
        restaurante: role.restaurante ? 1 : 0,
        clientes: role.clientes ? 1 : 0,
        empresa: role.empresa ? 1 : 0,
        pago: role.pago ? 1 : 0,
        id_empresa: idCompany
      });
    }
    
    deleteRole(id: number): Observable<any> {
      return this.http.delete<any>(`${this.apiUrl}/role`, {
        params: { id: id }
      });
    }
    
}

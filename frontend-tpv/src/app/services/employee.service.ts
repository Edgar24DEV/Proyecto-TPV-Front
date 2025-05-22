import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Restaurant } from '../models/Restaurant';
import { Observable, map } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Employee } from '../models/Employee';

@Injectable({
  providedIn: 'root',
})
export class EmployeeService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getListEmployeeRestaurant(id: number): Observable<Employee[]> {
    return this.http
      .get<any>(`${this.apiUrl}/employees`, {
        params: { id_restaurante: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }

          return rawData.map(
            (emp) =>
              new Employee({
                id: emp.id,
                nombre: emp.nombre,
                pin: emp.pin,
                idRol: emp.idRol,
                idEmpresa: emp.idEmpresa,
              })
          );
        })
      );
  }
  postLoginEmployee(id: number, pin: number): Observable<Employee> {
    return this.http
      .post<any>(`${this.apiUrl}/employees/login`, {
        id: id.toString(),
        pin: pin.toString(),
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          // Ahora asumimos que la respuesta es un solo objeto, no un array
          if (!rawData || !rawData.id) {
            throw new Error(
              'La respuesta del servidor no contiene un empleado válido'
            );
          }

          // Retorna un solo empleado en lugar de un array
          return new Employee({
            id: rawData.id,
            nombre: rawData.nombre,
            pin: rawData.pin,
            idRol: rawData.idRol,
            idEmpresa: rawData.idEmpresa,
          });
        })
      );
  }

  postEmployee(
    employee: Employee,
    idRestaurante: number
  ): Observable<Employee> {
    return this.http
      .post<any>(`${this.apiUrl}/employees`, {
        nombre: employee.nombre,
        pin: employee.pin,
        id_empresa: employee.idEmpresa,
        id_rol: employee.idRol,
        id_restaurante: idRestaurante,
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          // Ahora asumimos que la respuesta es un solo objeto, no un array
          if (!rawData || !rawData.id) {
            throw new Error(
              'La respuesta del servidor no contiene un empleado válido'
            );
          }

          // Retorna un solo empleado en lugar de un array
          return new Employee({
            id: rawData.id,
            nombre: rawData.nombre,
            pin: rawData.pin,
            idRol: rawData.idRol,
            idEmpresa: rawData.idEmpresa,
          });
        })
      );
  }

  putEmployee(employee: Employee): Observable<Employee> {
    return this.http
      .put<any>(`${this.apiUrl}/employees`, {
        id: employee.id,
        nombre: employee.nombre,
        pin: employee.pin,
        id_empresa: employee.idEmpresa,
        id_rol: employee.idRol,
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          // Ahora asumimos que la respuesta es un solo objeto, no un array
          if (!rawData || !rawData.id) {
            throw new Error(
              'La respuesta del servidor no contiene un empleado válido'
            );
          }

          // Retorna un solo empleado en lugar de un array
          return new Employee({
            id: rawData.id,
            nombre: rawData.nombre,
            pin: rawData.pin,
            idRol: rawData.idRol,
            idEmpresa: rawData.idEmpresa,
          });
        })
      );
  }

  getEmployee(id: number): Observable<Employee> {
    return this.http
      .get<any>(`${this.apiUrl}/employee`, {
        params: { id: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          return new Employee({
            id: rawData.id,
            nombre: rawData.nombre,
            pin: rawData.pin,
            idRol: rawData.idRol,
            idEmpresa: rawData.idEmpresa,
          });
        })
      );
  }

  deleteEmployee(id: number): Observable<Employee> {
    return this.http.delete<Employee>(`${this.apiUrl}/employees`, {
      params: { id: id },
    });
  }
  getListEmployeeCompany(id: number): Observable<Employee[]> {
    return this.http.get<any>(`${this.apiUrl}/employees-company`, {
      params: { id_empresa: id.toString() }  
    }).pipe(
      map(response => {
  
        const rawData = response?.data ?? response;
  
        if (!Array.isArray(rawData)) {
          throw new Error('La respuesta del servidor no es una lista');
        }
  
        return rawData.map(emp => new Employee({
          id: emp.id,
          nombre: emp.nombre,
          pin: emp.pin,
          idRol: emp.idRol,
          idEmpresa: emp.idEmpresa
        }));
      })
    );
  }
  postEmployeeRestaurant(
    idEmpleado: number,
    idRestaurante: number
  ): Observable<Employee> {
    return this.http
      .post<any>(`${this.apiUrl}/employees`, {
        activo: true,
        id_empleado: idEmpleado,
        id_restaurante: idRestaurante,
      });
  }
  postEmployeeRestaurantRelation(
    idEmpleado: number,
    idRestaurante: number
  ): Observable<Employee> {
    return this.http
      .post<any>(`${this.apiUrl}/employee-restaurant`, {
        id_empleado: idEmpleado,
        id_restaurante: idRestaurante,
      });
  }
  getListEmployeeRestaurants(idCompany: number, idEmployee: number): Observable<Restaurant[]> {
    return this.http.get<any>(`${this.apiUrl}/employee-restaurants`, {
      params: { 
        id_empresa: idCompany.toString(),
        id_empleado: idEmployee.toString(),
       }  
    }).pipe(
      map(response => {
  
        const rawData = response?.data ?? response;
  
        if (!Array.isArray(rawData)) {
          throw new Error('La respuesta del servidor no es una lista');
        }
  
        return rawData.map(item => new Restaurant({
          id: item.id,
          nombre: item.nombre,
          razonSocial: item.razonSocial, 
          direccion: item.direccion,
          telefono: item.telefono,
          contrasenya: item.contrasenya,
          direccionFiscal: item.direccionFiscal, 
          cif: item.cif, 
          idEmpresa: item.idEmpresa, 
        }));
      })
    );
  }
  deleteEmployeeRestaurant(idEmpleado: number, idRestaurante: number): Observable<Employee> {
    return this.http.delete<Employee>(`${this.apiUrl}/employee-restaurant`, {
      params: { id_empleado: idEmpleado,
        id_restaurante: idRestaurante,
       },
    });
  }
}

import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Category } from '../models/Category';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class CategoryService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getListCategoryRestaurant(idRestaurant: number): Observable<Category[]> {
    if (!idRestaurant || isNaN(idRestaurant)) {
      throw new Error('ID de restaurante no válido');
    }

    return this.http.get<any>(`${this.apiUrl}/categories`, {
      params: { id_restaurante: idRestaurant.toString() }
    }).pipe(
      map(response => this.mapCategoryListResponse(response))
    );
  }

  getListCategoryCompany(idCompany: number): Observable<Category[]> {
    if (!idCompany || isNaN(idCompany)) {
      throw new Error('ID de empresa no válido');
    }

    return this.http.get<any>(`${this.apiUrl}/company/categories`, {
      params: { id: idCompany.toString() }
    }).pipe(
      map(response => this.mapCategoryListResponse(response))
    );
  }

  postCategory(category: Category): Observable<Category> {
    if (!category || !category.idEmpresa) {
      throw new Error('Datos de categoría incompletos');
    }

    return this.http.post<any>(`${this.apiUrl}/categories`, {
      categoria: category.categoria,
      activo: category.activo,
      id_empresa: category.idEmpresa
    }).pipe(
      map(response => this.mapSingleCategoryResponse(response))
    );
  }

  putCategory(category: Category): Observable<Category> {
    if (!category || !category.id) {
      throw new Error('Datos de categoría incompletos');
    }

    return this.http.put<any>(`${this.apiUrl}/categories`, {
      id: category.id,
      categoria: category.categoria,
      activo: category.activo,
      id_empresa: category.idEmpresa
    }).pipe(
      map(response => this.mapSingleCategoryResponse(response))
    );
  }

  getCategory(id: number): Observable<Category> {
    if (!id || isNaN(id)) {
      throw new Error('ID de categoría no válido');
    }

    return this.http.get<any>(`${this.apiUrl}/category`, {
      params: { id: id.toString() }
    }).pipe(
      map(response => this.mapSingleCategoryResponse(response))
    );
  }

  deleteCategory(id: number): Observable<boolean> {
    if (!id || isNaN(id)) {
      throw new Error('ID de categoría no válido');
    }

    return this.http.delete<any>(`${this.apiUrl}/category`, {
      params: { id: id.toString() }
    }).pipe(
      map(response => response?.success || true)
    );
  }

  private mapCategoryListResponse(response: any): Category[] {
    const rawData = response?.data ?? response;

    if (!Array.isArray(rawData)) {
      throw new Error('La respuesta del servidor no es una lista válida');
    }

    return rawData.map(item => new Category({
      id: item.id,
      categoria: item.categoria,
      activo: item.activo,
      idEmpresa: item.idEmpresa
    }));
  }

  private mapSingleCategoryResponse(response: any): Category {
    const rawData = response?.data ?? response;

    if (!rawData || !rawData.id) {
      throw new Error('La respuesta del servidor no contiene una categoría válida');
    }

    return new Category({
      id: rawData.id,
      categoria: rawData.categoria,
      activo: rawData.activo,
      idEmpresa: rawData.idEmpresa
    });
  }
}
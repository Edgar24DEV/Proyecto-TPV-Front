import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, map } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Product } from '../models/Product';

@Injectable({
  providedIn: 'root',
})
export class ProductService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getListProductsRestaurant(id: number): Observable<Product[]> {
    return this.http
      .get<any>(`${this.apiUrl}/products`, {
        params: { id_restaurante: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }

          return rawData.map(
            (tempProduct) =>
              new Product({
                id: tempProduct.id,
                nombre: tempProduct.nombre,
                precio: tempProduct.precio,
                imagen: tempProduct.imagen,
                activo: tempProduct.activo,
                iva: tempProduct.iva,
                idCategoria: tempProduct.idCategoria,
                idEmpresa: tempProduct.idEmpresa,
              })
          );
        })
      );
  }

  getListProductsCompany(id: number): Observable<Product[]> {
    return this.http
      .get<any>(`${this.apiUrl}/company/products`, {
        params: { id_empresa: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }

          return rawData.map(
            (tempProduct) =>
              new Product({
                id: tempProduct.id,
                nombre: tempProduct.nombre,
                precio: tempProduct.precio,
                imagen: tempProduct.imagen,
                activo: tempProduct.activo,
                iva: tempProduct.iva,
                idCategoria: tempProduct.idCategoria,
                idEmpresa: tempProduct.idEmpresa,
              })
          );
        })
      );
  }

  getAllProductsRestaurant(id: number): Observable<Product[]> {
    return this.http
      .get<any>(`${this.apiUrl}/restaurant/products`, {
        params: { id_restaurante: id.toString() },
      })
      .pipe(
        map((response) => {
          const rawData = response?.data ?? response;

          if (!Array.isArray(rawData)) {
            throw new Error('La respuesta del servidor no es una lista');
          }

          return rawData.map(
            (tempProduct) =>
              new Product({
                id: tempProduct.id,
                nombre: tempProduct.nombre,
                precio: tempProduct.precio,
                imagen: tempProduct.imagen,
                activo: tempProduct.activo,
                iva: tempProduct.iva,
                idCategoria: tempProduct.idCategoria,
                idEmpresa: tempProduct.idEmpresa,
              })
          );
        })
      );
  }

  getProduct(id: number): Observable<Product> {
    return this.http
      .get<any>(`${this.apiUrl}/product`, {
        params: { id: id.toString() },
      })
      .pipe(
        map((response) => {
          const tempProduct = response?.data ?? response;

          return new Product({
            id: tempProduct.id,
            nombre: tempProduct.nombre,
            precio: tempProduct.precio,
            imagen: tempProduct.imagen,
            activo: tempProduct.activo,
            iva: tempProduct.iva,
            idCategoria: tempProduct.idCategoria,
            idEmpresa: tempProduct.idEmpresa,
          });
        })
      );
  }

  postProduct(product: Product, idCompany: number): Observable<Product> {
    return this.http
      .post<any>(`${this.apiUrl}/products`, {
        nombre: product.nombre,
        precio: product.precio,
        imagen: product.imagen,
        iva: product.iva,
        id_categoria: product.idCategoria,
        id_empresa: idCompany,
      })
      .pipe(
        map((response) => {
          const tempProduct = response?.data ?? response;

          // Ahora asumimos que la respuesta es un solo objeto, no un array
          if (!tempProduct || !tempProduct.id) {
            throw new Error(
              'La respuesta del servidor no contiene un empleado válido'
            );
          }

          // Retorna un solo empleado en lugar de un array
          return new Product({
            id: tempProduct.id,
            nombre: tempProduct.nombre,
            precio: tempProduct.precio,
            imagen: tempProduct.imagen,
            activo: tempProduct.activo,
            iva: tempProduct.iva,
            idCategoria: tempProduct.idCategoria,
            idEmpresa: tempProduct.idEmpresa,
          });
        })
      );
  }

  postProductRestaurant(
    product: Product,
    idRestaurant: number
  ): Observable<any> {
    return this.http
      .post<any>(`${this.apiUrl}/restaurant-product`, {
        id_producto: product.id,
        activo: product.activo,
        id_restaurante: idRestaurant,
      })
      .pipe(map((response) => {}));
  }

  getProductRestaurant(
    id: number,
    idRestaurant: number
  ): Observable<any> {
    return this.http
      .get<any>(`${this.apiUrl}/restaurant/product`, {
        params: { id_restaurante: idRestaurant, id_producto: id },
      })
      .pipe(
        map((response) => {
          return response;
        }
      )
      );
  }

  putProductRestaurant(
    product: Product,
    active: boolean,
    idRestaurant: number
  ): Observable<any> {
    return this.http
      .put<any>(`${this.apiUrl}/restaurant/product`, {
        id_producto: product.id,
        activo: active,
        id_restaurante: idRestaurant,
      })
      .pipe(map((response) => {}));
  }

  uploadImage(data: FormData) {
    return this.http.post<{ path: string }>(`${this.apiUrl}/upload`, data);
  }

  putProduct(product: Product): Observable<Product> {
    return this.http
      .put<any>(`${this.apiUrl}/product`, {
        id: product.id,
        nombre: product.nombre,
        precio: product.precio,
        imagen: product.imagen,
        iva: product.iva,
        activo: product.activo,
        id_categoria: product.idCategoria,
      })
      .pipe(
        map((response) => {
          const tempProduct = response?.data ?? response;
          return new Product({
            id: tempProduct.id,
            nombre: tempProduct.nombre,
            precio: tempProduct.precio,
            imagen: tempProduct.imagen,
            activo: tempProduct.activo,
            iva: tempProduct.iva,
            idCategoria: tempProduct.idCategoria,
            idEmpresa: tempProduct.idEmpresa,
          });
        })
      );
  }
/*
  putProductCompany(product: Product): Observable<Product> {
    return this.http
      .put<any>(`${this.apiUrl}/product`, {
        id: product.id,
        nombre: product.nombre,
        precio: product.precio,
        imagen: product.imagen,
        iva: product.iva,
        activo: product.activo,
        id_categoria: product.idCategoria,
      })
      .pipe(
        map((response) => {
          const tempProduct = response?.data ?? response;
          return new Product({
            id: tempProduct.id,
            nombre: tempProduct.nombre,
            precio: tempProduct.precio,
            imagen: tempProduct.imagen,
            activo: tempProduct.activo,
            iva: tempProduct.iva,
            idCategoria: tempProduct.idCategoria,
            idEmpresa: tempProduct.idEmpresa,
          });
        })
      );
  }
*/
  deleteProduct(id: number): Observable<Product> {
    return this.http.delete<Product>(`${this.apiUrl}/product`, {
      params: { id: id },
    });
  }
  postProductRestaurantRelation(
      idProducto: number,
      idRestaurante: number
    ): Observable<any> {
      return this.http
        .post<any>(`${this.apiUrl}/restaurant-product`, {
          activo: true,
          id_producto: idProducto,
          id_restaurante: idRestaurante,
        });
    }
}

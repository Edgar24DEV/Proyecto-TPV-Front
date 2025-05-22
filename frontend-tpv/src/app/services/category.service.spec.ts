import { TestBed } from '@angular/core/testing';
import { CategoryService } from './category.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Category } from '../models/Category';
import { environment } from 'src/environments/environment';

describe('CategoryService', () => {
  let service: CategoryService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        CategoryService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(CategoryService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  describe('getListCategoryRestaurant', () => {
    it('should get categories by restaurant id', () => {
      const mockCategories = [
        { id: 1, categoria: 'Entrantes', activo: true, idEmpresa: 2 },
        { id: 2, categoria: 'Postres', activo: true, idEmpresa: 2 }
      ];

      service.getListCategoryRestaurant(5).subscribe(categories => {
        expect(categories.length).toBe(2);
        expect(categories[0]).toBeInstanceOf(Category);
        expect(categories[0].categoria).toBe('Entrantes');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/categories?id_restaurante=5`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockCategories });
    });

    it('should handle invalid response structure', () => {
      service.getListCategoryRestaurant(5).subscribe({
        error: (err) => {
          expect(err.message).toBe('La respuesta del servidor no es una lista válida');
        }
      });

      const req = httpMock.expectOne(`${mockApiUrl}/categories?id_restaurante=5`);
      req.flush({ data: {} });
    });
  });

  describe('getCategory', () => {
    it('should get category by id', () => {
      const mockCategory = { id: 1, categoria: 'Bebidas', activo: true, idEmpresa: 2 };

      service.getCategory(1).subscribe(category => {
        expect(category).toBeInstanceOf(Category);
        expect(category.id).toBe(1);
        expect(category.categoria).toBe('Bebidas');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/category?id=1`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockCategory });
    });

    it('should handle missing category', () => {
      service.getCategory(1).subscribe({
        error: (err) => {
          expect(err.message).toBe('La respuesta del servidor no contiene una categoría válida');
        }
      });

      const req = httpMock.expectOne(`${mockApiUrl}/category?id=1`);
      req.flush({});
    });
  });

  describe('postCategory', () => {
    /*
    it('should create a new category', () => {
      const newCategory = new Category({ categoria: 'Bebidas', activo: true, idEmpresa: 1 });

      service.postCategory(newCategory).subscribe(category => {
        expect(category).toBeInstanceOf(Category);
        expect(category.categoria).toBe('Bebidas');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/categories`);
      expect(req.request.method).toBe('POST');
      expect(req.request.body).toEqual({
        categoria: 'Bebidas',
        activo: true,
        id_empresa: 1
      });

      req.flush({ data: newCategory });
    });

    */
    it('should handle missing required fields', () => {
      expect(() => service.postCategory(new Category({ categoria: 'Bebidas', activo: true })))
        .toThrowError('Datos de categoría incompletos');
    });
  });

  describe('putCategory', () => {
    it('should update an existing category', () => {
      const updatedCategory = new Category({ id: 1, categoria: 'Refrescos', activo: false, idEmpresa: 1 });

      service.putCategory(updatedCategory).subscribe(category => {
        expect(category).toBeInstanceOf(Category);
        expect(category.categoria).toBe('Refrescos');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/categories`);
      expect(req.request.method).toBe('PUT');
      expect(req.request.body).toEqual({
        id: 1,
        categoria: 'Refrescos',
        activo: false,
        id_empresa: 1
      });

      req.flush({ data: updatedCategory });
    });

    it('should handle missing category id', () => {
      expect(() => service.putCategory(new Category({ categoria: 'Nueva', activo: true })))
        .toThrowError('Datos de categoría incompletos');
    });
  });

  describe('deleteCategory', () => {
    it('should delete a category', () => {
      const categoryId = 1;

      service.deleteCategory(categoryId).subscribe(response => {
        expect(response).toBeTruthy();
      });

      const req = httpMock.expectOne(`${mockApiUrl}/category?id=1`);
      expect(req.request.method).toBe('DELETE');
      req.flush({ success: true });
    });

    it('should handle invalid category ID', () => {
      expect(() => service.deleteCategory(NaN))
        .toThrowError('ID de categoría no válido');
    });
  });
});

import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CategoriesListComponent } from './categories-list.component';
import { CategoryService } from 'src/app/services/category.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { Category } from 'src/app/models/Category';
import { of, throwError } from 'rxjs';

describe('CategoriesListComponent', () => {
  let component: CategoriesListComponent;
  let fixture: ComponentFixture<CategoriesListComponent>;
  let mockCategoryService: jasmine.SpyObj<CategoryService>;
  let mockRouter: jasmine.SpyObj<Router>;

  beforeEach(async () => {
    mockCategoryService = jasmine.createSpyObj('CategoryService', ['getListCategoryRestaurant']);
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);

    await TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(), CommonModule, CategoriesListComponent], // IMPORT CategoriesListComponent en lugar de declararlo
      providers: [
        { provide: CategoryService, useValue: mockCategoryService },
        { provide: Router, useValue: mockRouter },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(CategoriesListComponent);
    component = fixture.componentInstance;
  });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

 describe('ngOnInit', () => {
  it('should navigate to login if no restaurant ID is found in localStorage', () => {
  spyOn(localStorage, 'getItem').and.returnValue(null);
  spyOn(component, 'listOfCategories'); // Evita errores adicionales si `listOfCategories()` depende de `idRestaurant`

  component.ngOnInit();

  expect(mockRouter.navigate).toHaveBeenCalledWith(['/loginRestaurant']);
});


  it('should set the restaurant ID and fetch categories if localStorage contains an ID', () => {
    spyOn(localStorage, 'getItem').and.returnValue('5');
    
    // Simula la respuesta del servicio correctamente
    mockCategoryService.getListCategoryRestaurant.and.returnValue(of([{ id: 1, categoria: 'Bebidas' }] as Category[]));

    component.ngOnInit();

    expect(component.idRestaurant).toBe(5);
    expect(component.categories.length).toBe(1);
    expect(component.categories[0].categoria).toBe('Bebidas');
  });
});


  describe('listOfCategories', () => {
    it('should fetch categories successfully', () => {
      mockCategoryService.getListCategoryRestaurant.and.returnValue(of([{ id: 1, categoria: 'Postres' }] as Category[]));

      component.listOfCategories();

      expect(component.categories.length).toBe(1);
      expect(component.categories[0].categoria).toBe('Postres');
    });

    it('should handle errors in fetching categories', () => {
      spyOn(console, 'log');
      mockCategoryService.getListCategoryRestaurant.and.returnValue(throwError(() => new Error('API Error')));

      component.listOfCategories();

      expect(console.log).toHaveBeenCalledWith(new Error('API Error'));
    });
  });

  describe('selectCategory', () => {
    it('should toggle category selection correctly', () => {
      spyOn(component.categorySelected, 'emit');

      component.selectCategory(3);
      expect(component.selectedCategoryId).toBe(3);
      expect(component.categorySelected.emit).toHaveBeenCalledWith(3);

      component.selectCategory(3);
      expect(component.selectedCategoryId).toBeUndefined();
      expect(component.categorySelected.emit).toHaveBeenCalledWith(undefined);
    });
  });
});

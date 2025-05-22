import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CategoryAdminComponent } from './category-admin.component';
import { CategoryService } from 'src/app/services/category.service';
import { AlertService } from 'src/app/services/alert.service';
import { Router } from '@angular/router';
import { AlertController, IonicModule } from '@ionic/angular';
import { FormBuilder, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { of, throwError } from 'rxjs';
import { Category } from 'src/app/models/Category';

describe('CategoryAdminComponent', () => {
  let component: CategoryAdminComponent;
  let fixture: ComponentFixture<CategoryAdminComponent>;
  let mockCategoryService: jasmine.SpyObj<CategoryService>;
  let mockAlertService: jasmine.SpyObj<AlertService>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockAlertController: jasmine.SpyObj<AlertController>;
  let mockFormBuilder: FormBuilder;

  beforeEach(async () => {
    mockCategoryService = jasmine.createSpyObj('CategoryService', [
      'getListCategoryCompany',
      'postCategory',
      'putCategory',
      'deleteCategory',
      'getCategory',
    ]);
    mockAlertService = jasmine.createSpyObj('AlertService', ['show']);
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockAlertController = jasmine.createSpyObj('AlertController', ['create']);

    mockFormBuilder = new FormBuilder();

    await TestBed.configureTestingModule({
      imports: [
        IonicModule.forRoot(),
        CommonModule,
        ReactiveFormsModule,
        CategoryAdminComponent,
      ],
      providers: [
        { provide: CategoryService, useValue: mockCategoryService },
        { provide: AlertService, useValue: mockAlertService },
        { provide: Router, useValue: mockRouter },
        { provide: AlertController, useValue: mockAlertController },
        { provide: FormBuilder, useValue: mockFormBuilder },
      ],
    }).compileComponents();

    // 🔹 Asegurar que el servicio devuelva un Observable válido ANTES de crear el componente
    mockCategoryService.getListCategoryCompany.and.returnValue(
      of([{ id: 1, categoria: 'Postres' }] as Category[])
    );

    fixture = TestBed.createComponent(CategoryAdminComponent);
    component = fixture.componentInstance;
  });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });
describe('ngOnInit with different localStorage scenarios', () => {
  function mockLocalStorageValues(values: Partial<Record<string, string | null>>) {
    spyOn(localStorage, 'getItem').and.callFake((key: string) => {
      return values[key] ?? null;
    });
  }

  const mockEmployeeRol = {
    idEmpresa: 99,
    categorias: true
  };

  it('should initialize correctly with idCompany and employeeRol', () => {
    mockLocalStorageValues({
      idCompany: '1',
      employeeRol: JSON.stringify(mockEmployeeRol)
    });

    component.ngOnInit();

    expect(component.idCompany).toBe(1);
    expect(component.employeeRol.idEmpresa).toBe(99);
    expect(mockRouter.navigate).not.toHaveBeenCalled();
  });

  it('should initialize correctly with idRestaurant and employeeRol', () => {
    mockLocalStorageValues({
      idRestaurant: '2',
      employeeRol: JSON.stringify(mockEmployeeRol)
    });

    component.ngOnInit();

    expect(component.idRestaurant).toBe(2);
    expect(component.employeeRol.idEmpresa).toBe(99);
    expect(mockRouter.navigate).not.toHaveBeenCalled();
  });


  it('should navigate to employees/panel if employeeRol has no categorias', () => {
    const badRol = { idEmpresa: 99, categorias: false };

    mockLocalStorageValues({
      idRestaurant: '2',
      employeeRol: JSON.stringify(badRol)
    });

    component.ngOnInit();

    expect(mockRouter.navigate).toHaveBeenCalledWith(['employees/panel']);
  });

  it('should navigate to root if idRestaurant is missing', () => {
    mockLocalStorageValues({
      employeeRol: JSON.stringify(mockEmployeeRol)
      // no idCompany or idRestaurant
    });

    component.ngOnInit();

    expect(mockRouter.navigate).toHaveBeenCalledWith(['']);
  });
});

});

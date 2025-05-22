import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';
import { Router } from '@angular/router';
import { ListRestaurantCompanyComponent } from './list-restaurant-company.component';
import { RestaurantService } from 'src/app/services/restaurant.service';
import { AlertService } from 'src/app/services/alert.service';
import { FormBuilder } from '@angular/forms';
import { AppComponent } from 'src/app/app.component';
import { of, throwError } from 'rxjs';
import { Restaurant } from 'src/app/models/Restaurant';

describe('ListRestaurantCompanyComponent', () => {
  let component: ListRestaurantCompanyComponent;
  let fixture: ComponentFixture<ListRestaurantCompanyComponent>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockRestaurantService: jasmine.SpyObj<RestaurantService>;
  let mockAlertService: jasmine.SpyObj<AlertService>;
  let mockAppComponent: jasmine.SpyObj<AppComponent>;

  beforeEach(waitForAsync(() => {
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockRestaurantService = jasmine.createSpyObj('RestaurantService', [
      'getListRestaurantCompany',
      'postRestaurant',
      'putRestaurant',
      'deleteRestaurant',
    ]);
    mockAlertService = jasmine.createSpyObj('AlertService', ['show']);
    mockAppComponent = jasmine.createSpyObj('AppComponent', ['reloadHeader']);

    TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(), ListRestaurantCompanyComponent],
      providers: [
        { provide: Router, useValue: mockRouter },
        { provide: RestaurantService, useValue: mockRestaurantService },
        { provide: AlertService, useValue: mockAlertService },
        { provide: FormBuilder, useValue: new FormBuilder() },
        { provide: AppComponent, useValue: mockAppComponent },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(ListRestaurantCompanyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  describe('ngOnInit', () => {
    it('should navigate to login if no company ID is stored', () => {
      spyOn(localStorage, 'getItem').and.returnValue(null);
      component.ngOnInit();
      expect(mockRouter.navigate).toHaveBeenCalledWith(['/loginCompany']);
    });

    it('should initialize company data correctly', () => {
      spyOn(localStorage, 'getItem').and.callFake((key) => {
        return key === 'idCompany'
          ? '1'
          : key === 'company'
          ? 'Test Company'
          : null;
      });

      mockRestaurantService.getListRestaurantCompany.and.returnValue(
        of([{
          id: 1,
          nombre: 'Nuevo Restaurante',
          contrasenya: 'Pass123!',
          direccion: 'Calle Falsa 123',
          telefono: '123456789',
          direccionFiscal: 'Calle Fiscal 456',
          cif: 'A12345678',
          razonSocial: 'Restaurantes S.A.',
        } as Restaurant])
      );

      component.ngOnInit();

      expect(component.idCompany).toBe(1);
      expect(component.companyName).toBe('Test Company');
      expect(component.listRestaurants.length).toBe(1);
    });
  });

describe('listOfRestaurants', () => {
  it('should fetch restaurants successfully', async () => {
    component.idCompany = 1;

    mockRestaurantService.getListRestaurantCompany.and.returnValue(of([
      new Restaurant({
        id: 1,
        nombre: 'Nuevo Restaurante',
        contrasenya: 'Pass123!',
        direccion: 'Calle Falsa 123',
        telefono: '123456789',
        direccionFiscal: 'Calle Fiscal 456',
        cif: 'A12345678',
        razonSocial: 'Restaurantes S.A.',
        idEmpresa: 1
      })
    ]));

    component.listOfRestaurants();
    fixture.detectChanges(); // 🔹 Actualiza la vista

    await fixture.whenStable(); // 🔹 Espera a que la suscripción se complete

    console.log('listRestaurants:', component.listRestaurants); // 🔹 Verifica si la lista realmente tiene datos

    expect(component.listRestaurants.length).toBeGreaterThan(0);
    expect(component.listRestaurants[0].nombre).toBe('Nuevo Restaurante');
  });


  it('should navigate to loginCompany if restaurant fetch fails', () => {
    mockRestaurantService.getListRestaurantCompany.and.returnValue(throwError(() => new Error('API Error')));

    component.listOfRestaurants();

    expect(mockRouter.navigate).toHaveBeenCalledWith(['/loginCompany']);
  });
});


  describe('restaurantLogin', () => {
    it('should store restaurant data and navigate to employees', () => {
      const restaurant = new Restaurant({
        id: 1,
        nombre: 'Nuevo Restaurante',
        contrasenya: 'Pass123!',
        direccion: 'Calle Falsa 123',
        telefono: '123456789',
        direccionFiscal: 'Calle Fiscal 456',
        cif: 'A12345678',
        razonSocial: 'Restaurantes S.A.',
      });

      spyOn(localStorage, 'setItem');
      spyOn(localStorage, 'removeItem');

      component.restaurantLogin(restaurant);

      expect(localStorage.setItem).toHaveBeenCalledWith(
  'restaurant',
  'Nuevo Restaurante'
);

      expect(localStorage.setItem).toHaveBeenCalledWith('idRestaurant', '1');
      expect(localStorage.removeItem).toHaveBeenCalledWith('idCompany');
      expect(localStorage.removeItem).toHaveBeenCalledWith('company');
      expect(mockAppComponent.reloadHeader).toHaveBeenCalled();
      expect(mockRouter.navigate).toHaveBeenCalledWith(['employees']);
    });
  });

  describe('addRestaurant', () => {
    it('should reset form and show restaurant modal', () => {
      spyOn(component.restaurantForm, 'reset');

      component.addRestaurant();

      expect(component.restaurantForm.reset).toHaveBeenCalled();
      expect(component.showRestaurantModal).toBeTrue();
    });
  });

  describe('saveRestaurant', () => {
    it('should create a new restaurant successfully', () => {
      mockRestaurantService.postRestaurant.and.returnValue(
        of({
          id: 1,
          nombre: 'Nuevo Restaurante',
          contrasenya: 'Pass123!',
          direccion: 'Calle Falsa 123',
          telefono: '123456789',
          direccionFiscal: 'Calle Fiscal 456',
          cif: 'A12345678',
          razonSocial: 'Restaurantes S.A.',
        } as Restaurant)
      );

      component.saveRestaurant();

      expect(mockRestaurantService.postRestaurant).toHaveBeenCalled();
      expect(component.showRestaurantModal).toBeFalse();
    });

    it('should show an error alert if creation fails', () => {
      mockRestaurantService.postRestaurant.and.returnValue(
        throwError(() => new Error('API Error'))
      );

      component.saveRestaurant();

      expect(mockAlertService.show).toHaveBeenCalledWith(
        'Error',
        'No se pudo crear el restaurante correctamente',
        'error'
      );
    });
  });

  describe('updateRestaurant', () => {
    it('should update restaurant successfully', () => {
      mockRestaurantService.putRestaurant.and.returnValue(
         of({
          id: 1,
          nombre: 'Nuevo Restaurante',
          contrasenya: 'Pass123!',
          direccion: 'Calle Falsa 123',
          telefono: '123456789',
          direccionFiscal: 'Calle Fiscal 456',
          cif: 'A12345678',
          razonSocial: 'Restaurantes S.A.',
        } as Restaurant)
      );

      component.updateRestaurant();

      expect(mockRestaurantService.putRestaurant).toHaveBeenCalled();
      expect(component.editRestaurantModal).toBeFalse();
    });

    it('should show an error alert if update fails', () => {
      mockRestaurantService.putRestaurant.and.returnValue(
        throwError(() => new Error('API Error'))
      );

      component.updateRestaurant();

      expect(mockAlertService.show).toHaveBeenCalledWith(
        'Error',
        'No se pudo actualizar el restaurante correctamente',
        'error'
      );
    });
  });

  describe('darBajaRestaurante', () => {
    it('should delete a restaurant successfully', () => {
  mockRestaurantService.deleteRestaurant.and.returnValue(of(true));

  spyOn(component, 'listOfRestaurants'); // 🔹 Espía la función antes de llamarla

  component.darBajaRestaurante();

  expect(mockRestaurantService.deleteRestaurant).toHaveBeenCalled();
  expect(component.listOfRestaurants).toHaveBeenCalled(); // 🔹 Ahora sí es un `spy`
});


    it('should show an error alert if delete fails', () => {
      mockRestaurantService.deleteRestaurant.and.returnValue(
        throwError(() => new Error('API Error'))
      );

      component.darBajaRestaurante();

      expect(mockAlertService.show).toHaveBeenCalledWith(
        'Error',
        'No se pudo eliminar el restaurante correctamente',
        'error'
      );
    });
  });

  describe('logout', () => {
    it('should clear local storage and navigate to login', () => {
      spyOn(localStorage, 'clear');

      component.logout();

      expect(localStorage.clear).toHaveBeenCalled();
      expect(mockRouter.navigate).toHaveBeenCalledWith(['loginCompany']);
    });
  });

  describe('form validation', () => {
    it('should check if a form control is invalid', () => {
      component.formSubmitted = true;
      component.restaurantForm.controls['nombre'].setValue('');
      expect(component.isInvalid('nombre')).toBeTrue();
    });

    it('should check if a form control is valid', () => {
      component.formSubmitted = true;
      component.restaurantForm.controls['nombre'].setValue('Restaurante');
      expect(component.isInvalid('nombre')).toBeFalse();
    });
  });
});

import { ComponentFixture, fakeAsync, TestBed, tick, waitForAsync } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';
import { OrderLineService } from 'src/app/services/order-line.service';
import { OrderService } from 'src/app/services/order.service';
import { Router } from '@angular/router';
import { AlertController } from '@ionic/angular';
import { of } from 'rxjs';
import { TpvComponent } from '../tpv/tpv.component';
import { CommonModule } from '@angular/common';
import { CategoryService } from 'src/app/services/category.service';
import { HttpClientModule } from '@angular/common/http';

describe('TpvComponent', () => {
  let component: TpvComponent;
  let fixture: ComponentFixture<TpvComponent>;
  let orderLineServiceSpy: jasmine.SpyObj<OrderLineService>;
  let orderServiceSpy: jasmine.SpyObj<OrderService>;
  let categoryServiceSpy: jasmine.SpyObj<CategoryService>;
  let routerSpy: jasmine.SpyObj<Router>;
  let alertControllerSpy: jasmine.SpyObj<AlertController>;

  beforeEach(waitForAsync(() => {
    orderLineServiceSpy = jasmine.createSpyObj('OrderLineService', [
      'putOrderLines',
      'getListOrderLines',
    ]);
    orderServiceSpy = jasmine.createSpyObj('OrderService', ['putOrderDiners']);
    categoryServiceSpy = jasmine.createSpyObj('CategoryService', [
      'getCategories',
      'getListCategoryRestaurant',
    ]);
    routerSpy = jasmine.createSpyObj('Router', ['navigate']);
    alertControllerSpy = jasmine.createSpyObj('AlertController', ['create']);

    categoryServiceSpy.getListCategoryRestaurant.and.returnValue(of([]));
    alertControllerSpy.create.and.returnValue(
      Promise.resolve({
        present: jasmine.createSpy('present'),
        dismiss: jasmine.createSpy('dismiss'),
        onDidDismiss: jasmine
          .createSpy('onDidDismiss')
          .and.returnValue(Promise.resolve()),
        onWillDismiss: jasmine
          .createSpy('onWillDismiss')
          .and.returnValue(Promise.resolve()),
      } as unknown as HTMLIonAlertElement)
    );

    TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(), HttpClientModule, CommonModule],
      providers: [
        { provide: OrderLineService, useValue: orderLineServiceSpy },
        { provide: OrderService, useValue: orderServiceSpy },
        { provide: CategoryService, useValue: categoryServiceSpy },
        { provide: Router, useValue: routerSpy },
        { provide: AlertController, useValue: alertControllerSpy },
      ],
    }).compileComponents();
    orderLineServiceSpy.getListOrderLines.and.returnValue(of([]));
    fixture = TestBed.createComponent(TpvComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should load order data on init', () => {
    spyOn(component, 'loadOrderData');
    component.ngOnInit();
    expect(component.loadOrderData).toHaveBeenCalled();
  });

  it('should refresh order line list', () => {
    spyOn(component.orderLineListComponent, 'refreshLines');
    component.onRefreshOrderLine();
    expect(component.orderLineListComponent.refreshLines).toHaveBeenCalled();
  });

  it('should show alert on error', async () => {
    const alertSpy = jasmine.createSpyObj('HTMLIonAlertElement', ['present']);
    alertControllerSpy.create.and.returnValue(Promise.resolve(alertSpy));

    await component.showAlert('Error', 'Test error message');
    expect(alertControllerSpy.create).toHaveBeenCalledWith(
      jasmine.objectContaining({
        header: 'Error',
        message: 'Test error message',
      })
    );
    expect(alertSpy.present).toHaveBeenCalled();
  });

  it('should update guests count', () => {
    component.guests = 3;
    component.increaseGuests();
    expect(component.guests).toBe(4);
    component.decreaseGuests();
    expect(component.guests).toBe(3);
  });

  it('should navigate to payment on handlePayment', () => {
    component.handlePayment();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['/payment']);
  });

  it('should save order line with debounce', fakeAsync(() => {
  const orderLine = { id: 1, cantidad: 2, precio: 10 } as any;
  orderLineServiceSpy.putOrderLines.and.returnValue(of());

  component.saveLineWithDebounce(orderLine);

  // Avanzar el tiempo para ejecutar el debounce
  tick(100);

  expect(orderLineServiceSpy.putOrderLines).toHaveBeenCalledWith(orderLine);
}));

});

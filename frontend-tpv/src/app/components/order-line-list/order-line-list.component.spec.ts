import { TestBed, ComponentFixture, waitForAsync } from '@angular/core/testing';
import { OrderLineListComponent } from './order-line-list.component';
import { OrderLineService } from 'src/app/services/order-line.service';
import { AlertService } from 'src/app/services/alert.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { FormsModule, ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms';
import { of, throwError } from 'rxjs';
import { OrderLine } from 'src/app/models/OrderLine';

describe('OrderLineListComponent', () => {
  let component: OrderLineListComponent;
  let fixture: ComponentFixture<OrderLineListComponent>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockOrderLineService: jasmine.SpyObj<OrderLineService>;
  let mockAlertService: jasmine.SpyObj<AlertService>;

  beforeEach(waitForAsync(() => {
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockOrderLineService = jasmine.createSpyObj('OrderLineService', [
      'getListOrderLines',
      'putOrderLines',
      'deleteOrderLine'
    ]);
    mockAlertService = jasmine.createSpyObj('AlertService', ['show']);

    TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(), CommonModule, FormsModule, ReactiveFormsModule, OrderLineListComponent],
      providers: [
        { provide: Router, useValue: mockRouter },
        { provide: OrderLineService, useValue: mockOrderLineService },
        { provide: AlertService, useValue: mockAlertService },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(OrderLineListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  describe('ngOnInit', () => {
    it('should navigate to loginRestaurant if no idRestaurant or idCompany', () => {
      spyOn(localStorage, 'getItem').and.returnValue(null);
      component.ngOnInit();
      expect(mockRouter.navigate).toHaveBeenCalledWith(['/loginRestaurant']);
    });

    it('should initialize idRestaurant from localStorage', () => {
      spyOn(localStorage, 'getItem').and.callFake((key) => {
        if (key === 'idRestaurant') return '1';
        return null;
      });

      component.ngOnInit();

      expect(component.idRestaurant).toBe(1);
    });
  });

  describe('Order Line Management', () => {
    beforeEach(() => {
      component.orderLineForm = new FormGroup({
        cantidad: new FormControl('', [Validators.required]),
        precio: new FormControl('', [Validators.required]),
        nombre: new FormControl('', [Validators.required]),
      });
    });

    it('should delete an order line', () => {
      component.orderLineToDelete = { id: 1 } as OrderLine;

      mockOrderLineService.deleteOrderLine.and.returnValue(of());

      component.deleteOrderLine(1);

      expect(mockOrderLineService.deleteOrderLine).toHaveBeenCalledWith(1);
      expect(component.showDeleteModal).toBeFalse();
    });
  });
});

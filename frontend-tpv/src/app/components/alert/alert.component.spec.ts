import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AlertComponent } from './alert.component';
import { AlertService } from 'src/app/services/alert.service';
import { IonicModule } from '@ionic/angular';
import { AlertMessage } from 'src/app/interfaces/alert-message';
import { BehaviorSubject } from 'rxjs';

describe('AlertComponent', () => {
  let component: AlertComponent;
  let fixture: ComponentFixture<AlertComponent>;
  let mockAlertService: jasmine.SpyObj<AlertService>;
  let alertSubject: BehaviorSubject<AlertMessage | null>;

  beforeEach(async () => {
    alertSubject = new BehaviorSubject<AlertMessage | null>(null);
    mockAlertService = jasmine.createSpyObj('AlertService', [], {
      alert$: alertSubject.asObservable(),
    });

    await TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(), AlertComponent], // IMPORT AlertComponent en lugar de declararlo
      providers: [{ provide: AlertService, useValue: mockAlertService }],
    }).compileComponents();

    fixture = TestBed.createComponent(AlertComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create the alert component', () => {
    expect(component).toBeTruthy();
  });

  it('should subscribe to alert messages from AlertService', () => {
    const testAlert: AlertMessage = { title: 'Test Alert', message: 'This is a test alert', type: 'info' };
    alertSubject.next(testAlert);
    fixture.detectChanges();

    expect(component.alert).toEqual(testAlert);
  });

  it('should return the correct CSS class for the alert type', () => {
    expect(component.cssClass('success')).toBe('success');
    expect(component.cssClass('error')).toBe('error');
    expect(component.cssClass('warning')).toBe('warning');
    expect(component.cssClass('info')).toBe('info');
  });

  it('should clear the alert when AlertService emits null', () => {
    alertSubject.next(null);
    fixture.detectChanges();

    expect(component.alert).toBeNull();
  });
});

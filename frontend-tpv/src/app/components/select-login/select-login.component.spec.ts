import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';
import { SelectLoginComponent } from './select-login.component';
import { Router } from '@angular/router';
import { AppComponent } from 'src/app/app.component';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';

describe('SelectLoginComponent', () => {
  let component: SelectLoginComponent;
  let fixture: ComponentFixture<SelectLoginComponent>;
  let routerSpy: jasmine.SpyObj<Router>;
  let appComponentSpy: jasmine.SpyObj<AppComponent>;

  beforeEach(waitForAsync(() => {
    routerSpy = jasmine.createSpyObj('Router', ['navigate']);
    appComponentSpy = jasmine.createSpyObj('AppComponent', ['reloadHeader']);

    TestBed.configureTestingModule({
      imports: [
        IonicModule.forRoot(),
        CommonModule,
        FormsModule,
        ReactiveFormsModule,
        HttpClientModule,
      ],
      providers: [
        { provide: Router, useValue: routerSpy },
        { provide: AppComponent, useValue: appComponentSpy },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(SelectLoginComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should clear localStorage and reload header on init', () => {
    spyOn(localStorage, 'clear');

    component.ngOnInit();
    expect(localStorage.clear).toHaveBeenCalled();
    expect(appComponentSpy.reloadHeader).toHaveBeenCalled();
  });

  it('should clear localStorage and reload header on ionViewWillEnter', () => {
    spyOn(localStorage, 'clear');

    component.ionViewWillEnter();
    expect(localStorage.clear).toHaveBeenCalled();
    expect(appComponentSpy.reloadHeader).toHaveBeenCalled();
  });

  it('should navigate to loginRestaurant on redirectRestaurant', () => {
    component.redirectRestaurant();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['/loginRestaurant']);
  });

  it('should navigate to loginCompany on redirectAdministrate', () => {
    component.redirectAdministrate();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['/loginCompany']);
  });
});

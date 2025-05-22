import { ComponentFixture, TestBed, waitForAsync, tick, fakeAsync } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';
import { TpvKeyboardComponent } from './tpv-keyboard.component';

describe('TpvKeyboardComponent', () => {
  let component: TpvKeyboardComponent;
  let fixture: ComponentFixture<TpvKeyboardComponent>;
  let keyPressSpy: jasmine.Spy;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(),TpvKeyboardComponent],
    }).compileComponents();

    fixture = TestBed.createComponent(TpvKeyboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();

    // Espiamos el EventEmitter 'keyPressed' para saber cuando es llamado
    keyPressSpy = spyOn(component.keyPressed, 'emit');
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should emit keyPressed event when onKeyPress is called', fakeAsync(() => {
    const key = '1';
    component.onKeyPress(key);
    tick();

    expect(keyPressSpy).toHaveBeenCalledWith(key);
  }));
});

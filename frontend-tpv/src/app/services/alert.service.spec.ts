import { TestBed } from '@angular/core/testing';
import { AlertService } from './alert.service';
import { AlertMessage } from '../interfaces/alert-message';

describe('AlertService', () => {
  let service: AlertService;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [AlertService]
    });
    service = TestBed.inject(AlertService);
  });

  afterEach(() => {
    service.clear(); // Asegura que el estado se reinicia después de cada prueba
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should show an alert message', (done) => {
    const testMessage: AlertMessage = {
      title: 'Test Title',
      message: 'Test message',
      type: 'info'
    };

    service.alert$.subscribe(alert => {
      if (alert) {
        expect(alert).toEqual(testMessage);
        done();
      }
    });

    service.show(testMessage.title, testMessage.message, testMessage.type);
  });

it('should clear the alert message after 4 seconds', () => {
  jasmine.clock().install(); // Habilita la simulación de tiempo

  service.show('Warning', 'This is a warning message', 'warning');

  // Avanza el tiempo en 4001 ms para que `clear()` se ejecute
  jasmine.clock().tick(4001);

  // Verifica que el alert$ ahora sea `null`
  service.alert$.subscribe(alert => {
    expect(alert).toBeNull();
  });

  jasmine.clock().uninstall(); // Desactiva la simulación
});


  it('should clear the alert manually', () => {
    service.show('Error', 'This is an error message', 'error');
    service.clear();

    service.alert$.subscribe(alert => {
      expect(alert).toBeNull();
    });
  });
});

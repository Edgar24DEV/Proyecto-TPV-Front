import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { AlertMessage } from '../interfaces/alert-message';



@Injectable({ providedIn: 'root' })
export class AlertService {
  private alertSubject = new BehaviorSubject<AlertMessage | null>(null);
  alert$ = this.alertSubject.asObservable();

  show(title: string, message: string, type: AlertMessage['type'] = 'info') {
    this.alertSubject.next({ title, message, type });
    // auto-hide after 4 seconds
    setTimeout(() => this.clear(), 4000);
  }

  clear() {
    this.alertSubject.next(null);
  }
}

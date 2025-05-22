import { CommonModule } from '@angular/common';
import { Component, NgModule, OnInit } from '@angular/core';
import { NgModel } from '@angular/forms';
import { AlertMessage } from 'src/app/interfaces/alert-message';
import { AlertService } from 'src/app/services/alert.service';


@Component({
  selector: 'app-alert',
  standalone: true,
  templateUrl: './alert.component.html',
  styleUrls: ['./alert.component.scss'],
  imports: [CommonModule],
})

export class AlertComponent implements OnInit {
  alert: AlertMessage | null = null;

  constructor(private alertService: AlertService) {}

  ngOnInit(): void {
    this.alertService.alert$.subscribe((alert) => this.alert = alert);
  }

  cssClass(type: AlertMessage['type']) {
    return type;
  }
}

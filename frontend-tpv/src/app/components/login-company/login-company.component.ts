import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators, ReactiveFormsModule, FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { Company } from 'src/app/models/Company';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { CompanyService } from 'src/app/services/company.service';
import { AppComponent } from 'src/app/app.component'; // Importa AppComponent

@Component({
  selector: 'app-login-company',
  templateUrl: './login-company.component.html',
  styleUrls: ['./login-company.component.scss'],
  standalone: true,
  imports: [IonicModule, FormsModule, ReactiveFormsModule, CommonModule],
})
export class LoginCompanyComponent implements OnInit {
  company: Company | null = null;
  loginCompanyForm: FormGroup;
  mensajeError = false;

  constructor(
    private cs: CompanyService,
    private fb: FormBuilder,
    private router: Router,
    private appComponent: AppComponent // Inyecta AppComponent
  ) {
    this.loginCompanyForm = this.fb.group({
      nombre: new FormControl('', [Validators.required]),
      contrasenya: new FormControl('', [Validators.required])
    });
  }

  ngOnInit() {
    localStorage.clear();
    this.appComponent.reloadHeader();
  }

  ionViewWillEnter(){
    localStorage.clear();
    this.appComponent.reloadHeader();
  }

  loginCompany(): void {
    if (this.loginCompanyForm.valid) {
      const { nombre, contrasenya } = this.loginCompanyForm.value;
      this.cs.postLoginCompany(nombre, contrasenya).subscribe({
        next: (company: Company) => {
          this.company = company;
          this.mensajeError = false;
          if (this.company.nombre) {
            localStorage.setItem("company", this.company.nombre);
            localStorage.setItem('razonSocial', this.company.razonSocial!);
          }
          if (this.company.id) {
            localStorage.setItem("idCompany", String(this.company.id));
          }
          this.appComponent.reloadHeader(); // Llama a reloadHeader() aquí
          this.router.navigate(['restaurants']);
        },
        error: (error) => {
          this.mensajeError = true;
        }
      });
    } else {
      console.log('Formulario no válido');
    }
  }
  volver() {
    this.router.navigate(['select-login']);
  }
}

import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { HeaderTpvComponent } from '../header-tpv/header-tpv.component';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Category } from 'src/app/models/Category';
import { CategoryService } from 'src/app/services/category.service';
import { AlertController } from '@ionic/angular';
import { AppComponent } from 'src/app/app.component';
import { Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'app-category-admin',
  templateUrl: './category-admin.component.html',
  styleUrls: ['./category-admin.component.scss'],
  imports: [ IonicModule, CommonModule, ReactiveFormsModule],
})
export class CategoryAdminComponent implements OnInit {
  categoryForm!: FormGroup;
  employeeRol!: EmployeeRol;
  idCompany!: number;
  idRestaurant!: number;
  companyName!: string;
  listCategories: Category[] = [];
  filteredCategories: Category[] = [];
  category: Category = new Category({});
  isDisabled: boolean = false;
  showCategoryModal: boolean = false;
  createCategory: boolean = false;
  results: Category[] = [];
  selectedCategoryId?: number;

  constructor(
    private categoryService: CategoryService,
    private fb: FormBuilder,
    private alertController: AlertController,
    private router: Router,
    private alertService: AlertService
  ) {}

  ngOnInit() {
    this.initializeData();
    this.initForm();
    this.loadInitialData();
  }

  ionViewWillEnter() {
    this.initializeData();
    this.initForm();
    this.loadInitialData();

  }

  private initializeData() {
    const storedCompany = localStorage.getItem('idCompany');
    if (storedCompany) {
      this.idCompany = Number(storedCompany);
      this.companyName = localStorage.getItem('company')!;
    }

    const storedRestaurant = localStorage.getItem('idRestaurant');
    if (storedRestaurant && !storedCompany) {
      this.idRestaurant = Number(storedRestaurant);
    }

    const storedEmployeeRol = localStorage.getItem('employeeRol');
    if (storedEmployeeRol) {
      const plain = JSON.parse(storedEmployeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
    }

    if(!this.idCompany || this.idRestaurant){
      if(!this.employeeRol){
        this.router.navigate(['employees']);  
      }
      if(!this.employeeRol.categorias){
        this.router.navigate(['employees/panel']);
        return;
      }
      if(!this.idRestaurant){
        this.router.navigate(['']);
        }
    }
  }

  private initForm() {
    this.categoryForm = this.fb.group({
      categoria: ['', [
        Validators.required,
        Validators.maxLength(50),
        Validators.pattern(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-]+$/)
      ]],
      activo: [true, Validators.required]
    });
  }

  isInvalid(controlName: string): boolean {
    const control = this.categoryForm.get(controlName);
    return !!(control && control.invalid && (control.touched || this.categoryForm));
  }

  private loadInitialData() {
    this.listOfCategories();
  }

  listOfCategories() {
    if (!this.employeeRol?.idEmpresa && !this.idCompany) {
      this.alertService.show('Error', 'Fallo al listar las categorias', 'error');
      return;
    }

    this.categoryService.getListCategoryCompany((this.idCompany) ? this.idCompany! : this.employeeRol.idEmpresa!).subscribe({
      next: (data) => {
        this.listCategories = data;
        this.results = [...data];
        this.filteredCategories = [...data];
        this.filteredCategories.unshift({
          id: undefined,
          categoria: 'Todas las categorías',
          activo: true,
          idEmpresa: (this.idCompany) ? this.idCompany! : this.employeeRol.idEmpresa!,
        } as Category);
      },
      error: (err) => {
        this.alertService.show('Error', 'No se pudieron cargar las categorías', 'error');
      }
    });
  }

  async saveCategory() {
    if (this.categoryForm.invalid) {
      this.showFormErrors();
      return;
    }

    const newCategory = new Category({
      categoria: this.categoryForm.value.categoria.trim(),
      activo: this.categoryForm.value.activo,
      idEmpresa: (this.idCompany) ? this.idCompany! : this.employeeRol.idEmpresa!,
    });

    try {
      if (this.createCategory) {
        this.categoryService.postCategory(newCategory).subscribe({
          next:(data) => {
            this.showCategoryModal = false;
            this.listOfCategories();
          },
            error: (err) => {
              this.alertService.show('Error', 'Error al crear la categoria', 'error');
            },
          });
      } else {
        newCategory.id = this.category.id;
        this.categoryService.putCategory(newCategory).subscribe({
          next:(data) => {
            this.showCategoryModal = false;
            this.listOfCategories();
          },
            error: (err) => {
              this.alertService.show('Error', 'Error al actualizar la categoria', 'error');
            },
          });
      }

      this.showCategoryModal = false;
      this.listOfCategories();
    } catch (error) {
      this.alertService.show('Error', 'No se pudo guardar la categoría', 'error');
    }
  }

  async deleteCategory() {
    if (!this.category?.id) {
      this.alertService.show('Error', 'No se puede eliminar una categoría no existente', 'error');
      return;
    }

    const alert = await this.alertController.create({
      header: 'Confirmar eliminación',
      message: `¿Estás seguro de eliminar la categoría "${this.category.categoria}"?`,
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel'
        },
        {
          text: 'Eliminar',
          handler: async () => {
              this.categoryService.deleteCategory(this.category.id!).subscribe({
                next: (data) => {
                  this.showCategoryModal = false;
                  this.listOfCategories();
                },
                error: (err) => {
                  this.alertService.show('Error', 'Error al borrar la categoria', 'error');
                },
              });
              this.showCategoryModal = false;
              this.listOfCategories();
          }
        }
      ]
    });

    await alert.present();
  }

  showCreate() {
    this.category = new Category({});
    this.categoryForm.reset({ activo: true });
    this.showCategoryModal = true;
    this.createCategory = true;
    this.isDisabled = false;
  }

  showEdit(id: number) {
    this.getCategory(id);
    this.createCategory = false;
    this.isDisabled = false;
  }

  showInfo(id: number) {
    this.getCategory(id);
    this.createCategory = false;
    this.isDisabled = true;
  }

  getCategory(id: number) {
    if (!id || isNaN(id)) {
      this.alertService.show('Error', 'Fallo al cargar la categoria', 'error');
      return;
    }

    this.categoryService.getCategory(id).subscribe({
      next: (category) => {

        this.category = category;
        this.categoryForm.patchValue({
          categoria: category.categoria,
          activo: category.activo
        });
        this.showCategoryModal = true;
      },
      error: (err) => {
        this.alertService.show('Error', 'No se pudo cargar la categoría', 'error');
      }
    });
  }

  cancel() {
    this.showCategoryModal = false;
  }

  handleInput(event: Event) {
    const target = event.target as HTMLIonSearchbarElement;
    const query = target.value?.toLowerCase() || '';

    let filtered = [...this.listCategories];

    if (this.selectedCategoryId) {
      filtered = filtered.filter(c => c.id === this.selectedCategoryId);
    }

    this.results = filtered.filter(c =>
      c.categoria!.toLowerCase().includes(query)
    );
  }

  filterByCategory(categoryId?: number) {
    this.selectedCategoryId = categoryId;
    this.handleInput({ target: { value: '' } } as any);
  }


  private showFormErrors() {
    const errors = [];

    if (this.categoryForm.controls['categoria'].errors) {
      if (this.categoryForm.controls['categoria'].errors['required']) {
        errors.push('El nombre de la categoría es requerido');
      }
      if (this.categoryForm.controls['categoria'].errors['maxlength']) {
        errors.push('El nombre no puede exceder los 50 caracteres');
      }
      if (this.categoryForm.controls['categoria'].errors['pattern']) {
        errors.push('El nombre solo puede contener letras y espacios');
      }
    }

    this.alertService.show('Error', 'Error en el formulario' + errors.join('<br>') || 'Por favor complete el formulario correctamente', 'error');
    
  }
}

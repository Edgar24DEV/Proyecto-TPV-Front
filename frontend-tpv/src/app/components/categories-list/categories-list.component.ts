import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { Router } from '@angular/router';
import { Category } from 'src/app/models/Category';
import { CategoryService } from 'src/app/services/category.service';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';

@Component({
  selector: 'app-categories-list',
  templateUrl: './categories-list.component.html',
  styleUrls: ['./categories-list.component.scss'],
  imports: [ IonicModule, CommonModule],

})
export class CategoriesListComponent  implements OnInit {

  categories: Category[] = [];
  idRestaurant!: number;
  selectedCategoryId?: number;

  constructor( private categoryService: CategoryService, private router: Router) { }

  ngOnInit() {
    const storedRestaurantId = localStorage.getItem('idRestaurant');
    if (!storedRestaurantId) {
      this.router.navigate(['/loginRestaurant']);
    }
    this.idRestaurant = Number(storedRestaurantId);
    this.listOfCategories();
  }

  listOfCategories() {
    this.categoryService.getListCategoryRestaurant(this.idRestaurant).subscribe({
      next: (data) => {
        this.categories = data;
      },
      error: (err) => {
        console.log(err);
        // this.router.navigate(['/loginRestaurant']);
      },
    });
  }

  @Output() categorySelected = new EventEmitter<number>();


  selectCategory(categoryId: number) {
    if (this.selectedCategoryId === categoryId) {
      this.selectedCategoryId = undefined;
      this.categorySelected.emit(undefined); // <-- importante: mandamos undefined para indicar "sin filtro"
      return;
    }

    // Si no lo estaba, la seleccionamos normalmente
    this.selectedCategoryId = categoryId;
    this.categorySelected.emit(categoryId);
  }


}


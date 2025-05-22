import { Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import { ModalController, IonicModule } from '@ionic/angular';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-tpv-keyboard',
  templateUrl: './tpv-keyboard.component.html',
  styleUrls: ['./tpv-keyboard.component.scss'],
  imports: [
    CommonModule, IonicModule
  ],
})
export class TpvKeyboardComponent  implements OnInit {
  ngOnInit(): void {
  }
  @Output() keyPressed = new EventEmitter<string>();
  @Input() currentTotalInput: string = '';  // Recibe el valor actual del total
  @Input() currentQuantityInput: string = '';

  onKeyPress(key: string): void {
    this.keyPressed.emit(key);  // Emitimos la tecla presionada hacia el componente padre
  }

}

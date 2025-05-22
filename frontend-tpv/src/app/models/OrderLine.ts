export class OrderLine {
        private _id?: number;
        private _idPedido?: number;
        private _idProducto?: number;
        private _cantidad?: number;
        private _precio?: number;
        private _nombre?: string;
        private _observaciones?: string;
        private _estado?: string;
      
        constructor(data: {
          id?: number;
          idPedido?: number;
          idProducto?: number;
          cantidad?: number;
          precio?: number;
          nombre?: string;
          observaciones?: string;
          estado?: string;
        }){
          this._id = data.id;
          this._idPedido = data.idPedido;
          this._idProducto = data.idProducto;
          this._cantidad = data.cantidad;
          this._precio = data.precio;
          this._nombre = data.nombre;
          this._observaciones = data.observaciones;
          this._estado = data.estado;
        }
      
        // Getters
        get id(): number | undefined {
          return this._id;
        }
      
        get idPedido(): number | undefined {
          return this._idPedido;
        }
      
        get idProducto(): number | undefined {
          return this._idProducto;
        }
      
        get cantidad(): number | undefined {
          return this._cantidad;
        }
      
        get precio(): number | undefined {
          return this._precio;
        }
      
        get nombre(): string | undefined {
          return this._nombre;
        }
      
        get observaciones(): string | undefined {
          return this._observaciones;
        }
      
        get estado(): string | undefined {
          return this._estado;
        }
      
        // Setters con validación
        set id(value: number | undefined) {
          if (value && value > 0) this._id = value;
          else throw new Error('ID inválido');
        }
      
        set idPedido(value: number | undefined) {
          if (value && value > 0) this._idPedido = value;
          else throw new Error('ID de pedido inválido');
        }
      
        set idProducto(value: number | undefined) {
          if (value && value > 0) this._idProducto = value;
          else throw new Error('ID de producto inválido');
        }
      
        set cantidad(value: number | undefined) {
          if (value !== undefined && value >= 0) this._cantidad = value;
          else throw new Error('Cantidad inválida');
        }
      
        set precio(value: number | undefined) {
          if (value !== undefined && value >= 0) this._precio = value;
          else throw new Error('Precio inválido');
        }
      
        set nombre(value: string | undefined) {
          if (value && value.trim().length > 0) this._nombre = value;
          else throw new Error('Nombre inválido');
        }
      
        set observaciones(value: string | undefined) {
          if (value !== undefined) this._observaciones = value;
          else throw new Error('Observaciones inválidas');
        }
      
        set estado(value: string | undefined) {
          if (value && value.trim().length > 0) this._estado = value;
          else throw new Error('Estado inválido');
        }
      
      
}

export class Location {
    private _id?: number;
    private _ubicacion?: string;
    private _activo?: boolean;
    private _idRestaurante?: number;
  
    constructor(data: {
      id?: number;
      ubicacion?: string;
      activo?: boolean;
      idRestaurante?: number;
    }) {
      this._id = data.id;
      this._ubicacion = data.ubicacion;
      this._activo = data.activo;
      this._idRestaurante = data.idRestaurante;
    }
  
    // Getters
    get id(): number | undefined {
      return this._id;
    }
  
    get ubicacion(): string | undefined {
      return this._ubicacion;
    }
  
    get activo(): boolean | undefined {
      return this._activo;
    }
  
    get idRestaurante(): number | undefined {
      return this._idRestaurante;
    }
  
    // Setters con validación básica
    set id(value: number | undefined) {
      if (value && value > 0) this._id = value;
      else throw new Error('ID inválido');
    }
  
    set ubicacion(value: string | undefined) {
      if (value && value.trim().length > 0) this._ubicacion = value;
      else throw new Error('Ubicación inválida');
    }
  
    set activo(value: boolean | undefined) {
      if (typeof value === 'boolean') this._activo = value;
      else throw new Error('Valor de activo inválido');
    }
  
    set idRestaurante(value: number | undefined) {
      if (value && value > 0) this._idRestaurante = value;
      else throw new Error('ID de restaurante inválido');
    }
  }
  
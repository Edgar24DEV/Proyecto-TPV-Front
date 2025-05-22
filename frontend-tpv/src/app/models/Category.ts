export class Category {
    private _id?: number;
    private _categoria?: string;
    private _activo?: boolean;
    private _idEmpresa?: number;
  
    constructor(data: {
      id?: number;
      categoria?: string;
      activo?: boolean;
      idEmpresa?: number;
    }) {
      this._id = data.id;
      this._categoria = data.categoria;
      this._activo = data.activo;
      this._idEmpresa = data.idEmpresa;
    }
  
    // Getters
    get id(): number | undefined {
      return this._id;
    }
  
    get categoria(): string | undefined {
      return this._categoria;
    }
  
    get activo(): boolean | undefined {
      return this._activo;
    }
  
    get idEmpresa(): number | undefined {
      return this._idEmpresa;
    }
  
    // Setters con validación
    set id(value: number | undefined) {
      if (value && value > 0) this._id = value;
      else throw new Error('ID inválido');
    }
  
    set categoria(value: string | undefined) {
      if (value && value.trim().length > 0) this._categoria = value;
      else throw new Error('Categoría inválida');
    }
  
    set activo(value: boolean | undefined) {
      if (typeof value === 'boolean') this._activo = value;
      else throw new Error('Valor de activo inválido');
    }
  
    set idEmpresa(value: number | undefined) {
      if (value && value > 0) this._idEmpresa = value;
      else throw new Error('ID de empresa inválido');
    }
  }
  
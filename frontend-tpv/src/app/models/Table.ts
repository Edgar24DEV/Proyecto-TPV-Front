export class Table {
  private _id?: number;
  private _mesa?: string;
  private _activo?: boolean;
  private _idUbicacion?: number;
  private _pos_x?: number; // Coordenada X de la posición
  private _pos_y?: number; // Coordenada Y de la posición
  private _estado?: 'ocupada' | 'libre';
  private _nComensales?: number;

  constructor(data: {
    id?: number;
    mesa?: string;
    activo?: boolean;
    idUbicacion?: number;
    pos_x?: number; // Coordenada X
    pos_y?: number; // Coordenada Y
    estado?: 'ocupada' | 'libre';
    nComensales?: number;
  }) {
    this._id = data.id;
    this._mesa = data.mesa;
    this._activo = data.activo;
    this._idUbicacion = data.idUbicacion;
    this._pos_x = data.pos_x;
    this._pos_y = data.pos_y;
    this._estado = data.estado;
    this._nComensales = data.nComensales;
  }

  // Getters
  get id(): number | undefined {
    return this._id;
  }

  get mesa(): string | undefined {
    return this._mesa;
  }

  get activo(): boolean | undefined {
    return this._activo;
  }

  get idUbicacion(): number | undefined {
    return this._idUbicacion;
  }

  get pos_x(): number | undefined {
    return this._pos_x;
  }

  get pos_y(): number | undefined {
    return this._pos_y;
  }

  get estado(): 'ocupada' | 'libre' | undefined {
    return this._estado;
  }

  get nComensales(): number | undefined {
    return this._nComensales;
  }

  // Setters
  set id(value: number | undefined) {
    if (value && value > 0) this._id = value;
    else throw new Error('ID inválido');
  }

  set mesa(value: string | undefined) {
    if (value && value.trim().length > 0) this._mesa = value;
    else throw new Error('Nombre de mesa inválido');
  }

  set activo(value: boolean | undefined) {
    if (typeof value === 'boolean') this._activo = value;
    else throw new Error('Valor de activo inválido');
  }

  set idUbicacion(value: number | undefined) {
    if (value && value > 0) this._idUbicacion = value;
    else throw new Error('ID de ubicación inválido');
  }

  set pos_x(value: number | undefined) {
    if (value !== undefined && value >= 0) {
      this._pos_x = value;
    } else {
      throw new Error('Posición X inválida');
    }
  }

  set pos_y(value: number | undefined) {
    if (value !== undefined && value >= 0) {
      this._pos_y = value;
    } else {
      throw new Error('Posición Y inválida');
    }
  }

  set estado(value: 'ocupada' | 'libre' | undefined) {
    if (value === 'ocupada' || value === 'libre' || value === undefined) {
      this._estado = value;
    } else {
      throw new Error('Estado inválido');
    }
  }

  set nComensales(value: number | undefined) {
    if (value === undefined || (value >= 0 && Number.isInteger(value))) {
      this._nComensales = value;
    } else {
      throw new Error('Número de comensales inválido');
    }
  }
}

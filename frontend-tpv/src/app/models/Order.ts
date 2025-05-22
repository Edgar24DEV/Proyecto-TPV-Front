export class Order {
  private _id?: number;
  private _estado?: string;
  private _fechaInicio?: Date;
  private _fechaFin?: Date;
  private _comensales?: number;
  private _idMesa?: number;

  constructor(data: {
    id?: number;
    estado?: string;
    fechaInicio?: Date | string | { date: string };
    fechaFin?: Date | string | { date: string };
    comensales?: number;
    idMesa?: number;
  }) {
    this._id = data.id;
    this._estado = data.estado;
    
    // Manejo de fechaInicio
    this._fechaInicio = this.parseDate(data.fechaInicio);
    
    // Manejo de fechaFin
    this._fechaFin = this.parseDate(data.fechaFin);
    
    this._comensales = data.comensales;
    this._idMesa = data.idMesa;
  }

  private parseDate(dateInput: Date | string | { date: string } | undefined): Date | undefined {
    if (!dateInput) return undefined;
    
    let dateString: string;
    
    if (typeof dateInput === 'object' && 'date' in dateInput) {
      // Es el formato de Laravel { date: "..." }
      dateString = dateInput.date;
    } else if (dateInput instanceof Date) {
      // Ya es un objeto Date
      return dateInput;
    } else {
      // Es un string
      dateString = dateInput;
    }
    
    // Elimina los milisegundos si existen (000000)
    dateString = dateString.split('.')[0];
    
    // Crea el objeto Date
    const date = new Date(dateString);
    
    if (isNaN(date.getTime())) {
      console.error('Fecha inválida:', dateInput);
      return undefined;
    }
    
    return date;
  }

  // Getters
  get id(): number | undefined {
    return this._id;
  }
  get estado(): string | undefined {
    return this._estado;
  }
  get fechaInicio(): Date | undefined {
    return this._fechaInicio;
  }
  get fechaFin(): Date | undefined {
    return this._fechaFin;
  }
  get comensales(): number | undefined {
    return this._comensales;
  }
  get idMesa(): number | undefined {
    return this._idMesa;
  }

  // Setters con validación
  set id(value: number | undefined) {
    if (value === undefined || value > 0) this._id = value;
    else throw new Error('ID inválido');
  }

  set estado(value: string | undefined) {
    if (value === undefined || value.trim().length > 0) this._estado = value;
    else throw new Error('Estado inválido');
  }

  set fechaInicio(value: Date | string | undefined) {
    if (value === undefined) {
      this._fechaInicio = undefined;
    } else {
      const date = new Date(value);
      if (isNaN(date.getTime())) throw new Error('Fecha de inicio inválida');
      this._fechaInicio = date;
    }
  }

  set fechaFin(value: Date | string | undefined) {
    if (value === undefined) {
      this._fechaFin = undefined;
    } else {
      const date = new Date(value);
      if (isNaN(date.getTime())) throw new Error('Fecha de fin inválida');
      this._fechaFin = date;
    }
  }

  set comensales(value: number | undefined) {
    if (value === undefined || value >= 0) this._comensales = value;
    else throw new Error('Número de comensales inválido');
  }

  set idMesa(value: number | undefined) {
    if (value === undefined || value > 0) this._idMesa = value;
    else throw new Error('ID de mesa inválido');
  }

  static fromJSON(json: any): Order {
    return new Order({
      id: json._id ?? json.id,
      estado: json._estado ?? json.estado,
      fechaInicio: json._fechaInicio ?? json.fechaInicio,
      fechaFin: json._fechaFin ?? json.fechaFin,
      comensales: json._comensales ?? json.comensales,
      idMesa: json._idMesa ?? json.idMesa,
    });
  }
}

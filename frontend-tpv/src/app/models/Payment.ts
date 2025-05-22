export class Payment {
    private _id?: number;
    private _tipo?: string;
    private _cantidad?: number;
    private _fecha?: string;
    private _idPedido?: number;
    private _idCliente?: number;
    private _razonSocial?: string;
    private _cif?: string;
    private _nFactura?: string;
    private _correo?: string;
    private _direccionFiscal?: string;
  
    constructor(data: {
        id?: number;
      tipo?: string;
      cantidad?: number;
      fecha?: string;
      idPedido?: number;
      idCliente?: number;
      razonSocial?: string;
      cif?: string;
      nFactura?: string;
      correo?: string;
      direccionFiscal?: string;
    }) {
        this._id=data.id;
      this._tipo = data.tipo;
      this._cantidad = data.cantidad;
      this._fecha = data.fecha;
      this._idPedido = data.idPedido;
      this._idCliente = data.idCliente;
      this._razonSocial = data.razonSocial;
      this._cif = data.cif;
      this._nFactura = data.nFactura;
      this._correo = data.correo;
      this._direccionFiscal = data.direccionFiscal;
    }

       // Getters
       get id(): number | undefined {
        return this._id;
      }
    
  
    // Getters
    get tipo(): string | undefined {
      return this._tipo;
    }
  
    get cantidad(): number | undefined {
      return this._cantidad;
    }
  
    get fecha(): string | undefined {
      return this._fecha;
    }
  
    get idPedido(): number | undefined {
      return this._idPedido;
    }
  
    get idCliente(): number | undefined {
      return this._idCliente;
    }
  
    get razonSocial(): string | undefined {
      return this._razonSocial;
    }
  
    get cif(): string | undefined {
      return this._cif;
    }
  
    get nFactura(): string | undefined {
      return this._nFactura;
    }
  
    get correo(): string | undefined {
      return this._correo;
    }
  
    get direccionFiscal(): string | undefined {
      return this._direccionFiscal;
    }
  
    // Setters
    set tipo(value: string | undefined) {
      if (value && value.trim().length > 0) this._tipo = value;
      else throw new Error('Tipo de pago inválido');
    }
  
    set cantidad(value: number | undefined) {
      if (value !== undefined && value >= 0) this._cantidad = value;
      else throw new Error('Cantidad inválida');
    }
  
    set fecha(value: string | undefined) {
      if (value && value.trim().length > 0) this._fecha = value;
      else throw new Error('Fecha inválida');
    }
  
    set idPedido(value: number | undefined) {
      if (value && value > 0) this._idPedido = value;
      else throw new Error('ID de pedido inválido');
    }
  
    set idCliente(value: number | undefined) {
      if (value === undefined || value > 0) this._idCliente = value;
      else throw new Error('ID de cliente inválido');
    }
  
    set razonSocial(value: string | undefined) {
      this._razonSocial = value?.trim();
    }
  
    set cif(value: string | undefined) {
      this._cif = value?.trim();
    }
  
    set nFactura(value: string | undefined) {
      this._nFactura = value?.trim();
    }
  
    set correo(value: string | undefined) {
      this._correo = value?.trim();
    }
  
    set direccionFiscal(value: string | undefined) {
      this._direccionFiscal = value?.trim();
    }
  }
  
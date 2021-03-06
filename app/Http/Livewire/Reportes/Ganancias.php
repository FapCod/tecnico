<?php

namespace App\Http\Livewire\Reportes;

use App\Models\ServicioTecnico;
use Carbon\Carbon;
use Livewire\Component;

class Ganancias extends Component
{
    public $ganancias,$gananciasdiarias,$gananciasmensuales,$gananciasanuales;
    public $mes, $dia, $ano,$clientes=[];
    public $fechainicio = '';
    public $fechafin = '';
    public function mount()
    {
        $this->fechainicio = '';
        $this->fechafin = '';
    }
    public function render()
    {
        if (!$this->fechainicio == '' || !$this->fechafin == '') {
            $fi = Carbon::parse($this->fechainicio)->format('Y-m-d 00:00:00');
            $ff = Carbon::parse($this->fechafin)->format('Y-m-d 23:59:59');
            $this->ganancias = ServicioTecnico::whereBetween('fechaentrada', [$fi, $ff])->sum('precio');
            $this->clientes= ServicioTecnico::whereBetween('servicio_tecnicos.fechaentrada', [$fi,$ff])->get();
            
            if (!$this->fechainicio == '' && !$this->fechafin == '') {
                $fi = Carbon::parse($this->fechainicio)->format('Y-m-d 00:00:00');
                $fecha=strtotime($fi);
                // $fechainicio = Carbon::parse($fechainicio)->format('Y-m-d');
                $this->ano = date('Y', $fecha);
                $this->mes = date('m', $fecha);
                $this->dia = date('d', $fecha);
                $this->gananciasdiarias= ServicioTecnico::whereDay('fechaentrada', '=', $this->dia)->sum('precio');
                $this->gananciasmensuales= ServicioTecnico::whereMonth('fechaentrada', '=', $this->mes)->sum('precio');
                $this->gananciasanuales= ServicioTecnico::whereYear('fechaentrada', '=', $this->ano)->sum('precio');
            }
        } else {
            $this->ganancias = ServicioTecnico::sum('precio');
            $this->ano = '';
            $this->mes = '';
            $this->dia = '';
            $this->gananciasdiarias='';
            $this->gananciasmensuales='';
            $this->gananciasanuales='';
            $this->clientes= ServicioTecnico::all();
        }
        $ganancias = $this->ganancias;
        $ano =  $this->ano;
        $mes =  $this->mes;
        $dia = $this->dia;
        $gananciasdiarias =  $this->gananciasdiarias;
        $gananciasmensuales =  $this->gananciasmensuales;
        $gananciasanuales=  $this->gananciasanuales;
        $clientes =  $this->clientes;
        return view('livewire.reportes.ganancias', compact('ganancias','gananciasdiarias','gananciasmensuales','gananciasanuales','clientes'));
    }
    public function resetear()
    {
        $this->fechainicio = '';
        $this->fechafin = '';
    }
}

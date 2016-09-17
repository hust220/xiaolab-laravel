@extends('layouts.rna')

@section('title', 'Home')

@section('rap_section')
<a id="title" href="{{url('home')}}">Home</a>
@endsection

 @section('head_section')
<link rel="stylesheet" type="text/css" href="{{url('/css/RNA2D.css')}}" media="all" />
<script src="{{url('/js/RNA2D.js')}}"></script>
@endsection

@section('main_section')
<div class='box'>
  <p>Cell is a complicated interaction network of biomolecules and other molecules. The aim of our group is to study computationally the structures, dynamics and interactions of biomolecules and explore how biomolecules use physical laws to realize their biological functions.    </p>
  <p>Our research interests include: </p>

<div id="header1">Method Development</div>

<ul>

<li>Developing methods of predicting three-dimensional noncoding RNA structures.
<div align="center"><img src="./image/rna.png" width="150"/></div></li>
</br>
<li>Developing biomolecular (protein-protein, RNA-protein and DNA-protein) docking method.
<div align="center"><img src="./image/docking.png" width="150"/></div>
</li>

</br>
<li>Developing methods of calculating(binding) free energy of biomolecules.
<div align="center"><img src="./image/fe.png" width="150"/></div>
</li>

</br>
</ul>

<div id="header1">Physics of Biological Functions</div>

<ul>

<li>Designing functional molecules based on interactions.
<div align="center"><img src="./image/design.png" width="150"/></div>
</li>

</br>
<li>Simulating the dynamics of biomolecules and their complexes.
<div align="center"><img src="./image/md.png" width="150"/></div>
</li>

</br>
<li>Understanding the organization and dynamics of biomolecular networks.
<div align="center"><img src="./image/network.png" width="150"/></div>

</li>

</br>
</ul>

</div>
@endsection



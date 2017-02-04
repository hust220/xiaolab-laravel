@extends('layouts.rna')

@section('header-top')
    <a id="title" href="/links">External Links</a>
@endsection

@section('content-main')
<div class="panel panel-default" v-for="block in blocks">
    <div class="panel-heading">
        <h3 class="panel-title">@{{block.title}}</h3>
    </div>
    <div class="panel-body">
        <div v-for="chunk in block.content">
            <h5>@{{chunk.title}}</h5>
            <div class="list-group">
                <a class="list-group-item" :href="item.href" v-for="item in chunk.content">
                    <div class="row">
                        <div class="col-sm-3">
                            <strong>@{{item.title}}</strong>
                        </div>
                        <div class="col-sm-9 hidden-xs">
                            @{{{item.intro}}}
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('main-js')
    <script type="text/javascript" src="/js/vue.min.js"></script>
    <script type="text/javascript">
        new Vue({
            el: "#vue-main",
            data: {
                blocks: [{
                    title: "RNA 2D structure prediction methods",
                    content: [{
                        title: "Single sequence based methods",
                        content: [{
                            title: "Mfold",
                            intro: "The mfold web server is one of the oldest web servers in computational molecular biology.",
                            href: "http://mfold.rna.albany.edu/?q=mfold"
                        }, {
                            title: "RNAfold",
                            intro: "The RNAfold web server will predict secondary structures of single stranded RNA or DNA sequences."+
                                   "Current limits are 7,500 nt for partition function calculations and 10,000 nt for minimum free energy only predicitions.",
                            href: "http://rna.tbi.univie.ac.at/cgi-bin/RNAfold.cgi"
                        }, {
                            title: "pKiss",
                            intro: "pKiss is a tool for folding RNA secondary structures, including two limited classes of pseudoknots. ",
                            href: "http://bibiserv.techfak.uni-bielefeld.de/pkiss"
                        }, {title: "CentroidFold",
                            intro: "CentroidFold based on a generalized centroid estimator is one of the most accurate tools for predicting RNA secondary structures." +
                                   "The predicted secondary structure is coloured according to base pairing probabilities. ",
                            href: "http://rtools.cbrc.jp/centroidfold/"
                        }, {title: "ContextFold",
                            intro: "Context Fold is an RNA secondary structure prediction tool. It applies feature-rich scoring models,"+
                                   "whose parameters were obtained after training on comprehensive datasets. ",
                            href: "https://www.cs.bgu.ac.il/~negevcb/contextfold/"
                        }],
                    }, {
                        title: "Multiple sequences based methods",
                        content: [{
                            title: "CentroidHomfold",
                            intro: "CentroidHomfold predicts RNA secondary structures by employing automatically collected homologous sequences of the target."+
                                   "Homologous sequences are collected from Rfam using LAST. If homologous sequences are available,"+
                                   "CentroidHomfold can predict secondary structures for the target sequence more accurately than CentroidFold"+
                                   "using homologous sequence information with the probabilistic consistency transformation for base-pairing probabilities. ",
                            href: "http://rtools.cbrc.jp/centroidhomfold/"
                        }, {
                            title: "RNAalifold",
                            intro: "It will predict a consensus secondary structure of a set of aligned sequences." +
                                   "Current limits are 3000 nt and 300 sequences for an alignment.",
                            href: "http://rna.tbi.univie.ac.at/cgi-bin/RNAalifold.cgi"
                        }],
                    }],
                }, {
                    title: "RNA Databases",
                    content: [{
                        title: "RNA 3D structure databases",
                        content: [{
                            title: "PDB",
                            intro: "This resource is powered by the Protein Data Bank archive-information about the 3D shapes of proteins,"+
                                   "nucleic acids, and complex assemblies that helps students and researchers understand all aspects of biomedicine"+
                                   "and agriculture, from protein synthesis to health and disease.  As a member of the wwPDB, the RCSB PDB curates "+
                                   "and annotates PDB data. The RCSB PDB builds upon the data by creating tools and resources for research and "+
                                   "education in molecular biology, structural biology, computational biology, and beyond.",
                            href: "http://www.rcsb.org/pdb/home/home.do"
                        }, {
                            title: "NDB",
                            intro: "The NDB contains information about experimentally-determined nucleic acids and complex assemblies."+
                                   "Use the NDB to perform searches based on annotations relating to sequence, structure and function,"+
                                   "and to download, analyze, and learn about nucleic acids.",
                            href: "http://ndbserver.rutgers.edu/"
                        }],
                    }, {
                        title: "RNA sequence and 2D databases",
                        content: [{
                            title: "Rfam",
                            intro: "The Rfam database is a collection of RNA families, each represented by multiple sequence alignments,"+
                                   "consensus secondary structures and covariance models (CMs).",
                            href: "http://rfam.xfam.org/"
                        }],
                    }],
                }]
            },
        })
    </script>
@endsection


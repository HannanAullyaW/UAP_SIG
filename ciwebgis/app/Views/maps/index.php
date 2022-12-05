<?php


$submit = [
    'name' => 'submit',
    'id' => 'submit',
    'value' => 'Pilih Data',
    'class' => 'btn btn-primary',
    'type' => 'submit'
];

?>
<?= $this->extend('layout') ?>

<?= $this->section('head') ?>
<script src="<?= base_url('leaflet/leaflet.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('leaflet/leaflet.css') ?>" />
<style>
    #maps {
        height: 500px;
    }

    .info {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
    }

    .info h4 {
        margin: 0 0 5px;
        color: #777;
    }

    .legend {
        line-height: 18px;
        color: #555;
    }

    .legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.7;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1>Peta Bandar Lampung</h1>
<div class="row">
    <div class="card">
            <div id="maps"></div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    var data = <?= json_encode($data) ?>;


    var map = L.map('maps').setView({
        lat: 0.7893,
        lon: 113.9213
    }, 5);


    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);

    L.marker({
        lat: 0.7893,
        lon: 113.9213
    }).bindPopup('Hello Bandar Lampung').addTo(map);

    var geojson = L.geoJson(data).addTo(map);


    //ketika berada diatas wilayah, infonya langsung keluar datanya, ga perlu di klik klik lagi
    function highlightFeature(e) {
        var layer = e.target;

        layer.setStyle({
            weight: 1,
            color: '#ff0000',
            dashArray: '',
            fillOpacity: 0.7
        });

        if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
            layer.bringToFront();
        }

        info.update(layer.feature.properties);
    }
    //----------------------------------------//

    //ketika meninggalkan wilayah pada peta, akan kembali seperti semula
    function resetHighlight(e) {
        geojson.resetStyle(e.target);
        info.update();
    }
    //----------------------------------------//

    //menampilkan informasi datanya tanpa di klik//
    var info = L.control();

    info.onAdd = function(map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };

    info.update = function(props) {
        this._div.innerHTML = '<h4>Bandar Lampung</h4>' + (props ?
            '<b>' + props.Propinsi + '</b><br />' + props.nilai + ' (000) ribu jiwa' :
            'Hover di atas wilayah');
    };

    info.addTo(map);
    //----------------------------------------//

    var legend = L.control({
        position: 'bottomright'
    });

    legend.onAdd = function(map) {

        var div = L.DomUtil.create('div', 'info legend'),
            grades = [0, (nilaiMax / 8) * 1, (nilaiMax / 8) * 2, (nilaiMax / 8) * 3, (nilaiMax / 8) * 4, (nilaiMax / 8) * 5, (nilaiMax / 8) * 6, (nilaiMax / 8) * 7],
            labels = [];

        // loop through our density intervals and generate a label with a colored square for each interval
        for (var i = 0; i < grades.length; i++) {
            div.innerHTML +=
                '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
                grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
        }

        return div;
    };

    legend.addTo(map);
</script>
<?= $this->endSection() ?>
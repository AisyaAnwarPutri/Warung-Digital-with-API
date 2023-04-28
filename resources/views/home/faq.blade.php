@extends('layout.home')

@section('title', 'FAQ')

@section('content')
<!-- FAQ -->
<section class="section-wrap faq">
    <div class="container">
        <div class="row">

            <div class="col-sm-9">
                <h2 class="mb-20"><small>Pertanyaan Pengiriman</small></h2>

                <div class="panel-group accordion mb-50" id="accordion-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <a data-toggle="collapse" data-parent="#accordion-1" href="#collapse-1" class="minus">bagaimana
                                lacak pengiriman saya?<span>&nbsp;</span>
                            </a>
                        </div>
                        <div id="collapse-1" class="panel-collapse collapse in">
                            {{-- <div class="panel-body">
                                Our Theme is a very slick and clean e-commerce template with endless possibilities.
                                Creating an awesome website. Canna Theme is a very slick and clean e-commerce template
                                with endless possibilities.
                            </div> --}}
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-heading">
                            <a data-toggle="collapse" data-parent="#accordion-1" href="#collapse-2" class="plus">Di mana
                                dapatkah saya menemukan nomor pelacakan?<span>&nbsp;</span>
                            </a>
                        </div>
                        <div id="collapse-2" class="panel-collapse collapse">
                            {{-- <div class="panel-body">
                                We possess within us two minds. So far I have written only of the conscious mind. I
                                would now like to introduce you to your second mind, the hidden and mysterious
                                subconscious. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.And finally the subconscious is the mechanism through which
                                thought impulses which are repeated regularly with feeling and emotion are quickened,
                                charged. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.
                            </div> --}}
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-heading">
                            <a data-toggle="collapse" data-parent="#accordion-1" href="#collapse-3" class="plus">Apa
                                metode pengiriman yang dapat saya gunakan?<span>&nbsp;</span>
                            </a>
                        </div>
                        <div id="collapse-3" class="panel-collapse collapse">
                            {{-- <div class="panel-body">
                                We possess within us two minds. So far I have written only of the conscious mind. I
                                would now like to introduce you to your second mind, the hidden and mysterious
                                subconscious. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.And finally the subconscious is the mechanism through which
                                thought impulses which are repeated regularly with feeling and emotion are quickened,
                                charged. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.
                            </div> --}}
                        </div>
                    </div>
                </div> <!-- end accordion -->


                <h2 class="mb-20 mt-80"><small>pertanyaan Pembayaran</small></h2>

                <div class="panel-group accordion mb-50" id="accordion-2">
                    <div class="panel">
                        <div class="panel-heading">
                            <a data-toggle="collapse" data-parent="#accordion-2" href="#collapse-4" class="minus">Apa
                                metode pembayaran yang Anda terima?<span>&nbsp;</span>
                            </a>
                        </div>
                        <div id="collapse-4" class="panel-collapse collapse in">
                            {{-- <div class="panel-body">
                                Our Theme is a very slick and clean e-commerce template with endless possibilities.
                                Creating an awesome website. Canna Theme is a very slick and clean e-commerce template
                                with endless possibilities.
                            </div> --}}
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-heading">
                            <a data-toggle="collapse" data-parent="#accordion-2" href="#collapse-5" class="plus">bagaimana
                                membayar melalui kartu kredit?<span>&nbsp;</span>
                            </a>
                        </div>
                        <div id="collapse-5" class="panel-collapse collapse">
                            {{-- <div class="panel-body">
                                We possess within us two minds. So far I have written only of the conscious mind. I
                                would now like to introduce you to your second mind, the hidden and mysterious
                                subconscious. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.And finally the subconscious is the mechanism through which
                                thought impulses which are repeated regularly with feeling and emotion are quickened,
                                charged. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.
                            </div> --}}
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-heading">
                            <a data-toggle="collapse" data-parent="#accordion-2" href="#collapse-6" class="plus">Apa
                                kartu kredit yang Anda terima?<span>&nbsp;</span>
                            </a>
                        </div>
                        <div id="collapse-6" class="panel-collapse collapse">
                            {{-- <div class="panel-body">
                                We possess within us two minds. So far I have written only of the conscious mind. I
                                would now like to introduce you to your second mind, the hidden and mysterious
                                subconscious. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.And finally the subconscious is the mechanism through which
                                thought impulses which are repeated regularly with feeling and emotion are quickened,
                                charged. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.
                            </div> --}}
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-heading">
                            {{-- <a data-toggle="collapse" data-parent="#accordion-2" href="#collapse-7" class="plus">how to
                                pay via paypal?<span>&nbsp;</span>
                            </a> --}}
                        </div>
                        <div id="collapse-7" class="panel-collapse collapse">
                            {{-- <div class="panel-body">
                                We possess within us two minds. So far I have written only of the conscious mind. I
                                would now like to introduce you to your second mind, the hidden and mysterious
                                subconscious. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.And finally the subconscious is the mechanism through which
                                thought impulses which are repeated regularly with feeling and emotion are quickened,
                                charged. Our subconscious mind contains such power and complexity that it literally
                                staggers the imagination.
                            </div> --}}
                        </div>
                    </div>

                </div> <!-- end accordion -->

            </div> <!-- end col -->

            <aside class="sidebar col-sm-3">
                <div class="contact-item">
                    <h6>Kategori</h6>
                    <ul class="list-dividers">
                        <li>
                            <a href="#">Pengirimann</a>
                        </li>
                        <li>
                            <a href="#">Pembayaran</a>
                        </li>
                        <li>
                            <a href="#">Bantuan</a>
                        </li>
                        <li>
                            <a href="#">Pertanyaan Umum</a>
                        </li>
                    </ul>
                </div>

                <div class="contact-item">
                    <h6>Informasi</h6>
                    <ul>
                        <li>
                            <i class="fa fa-envelope"></i><a href="mailto:theme@support.com">warungdigitalbumdes@gmail.com</a>
                        </li>
                        <li>
                            {{-- <i class="fa fa-phone"></i><span>+1 (800) 888 5260 52 63</span> --}}
                        </li>
                        <li>
                            <i class="fa fa-skype"></i><span>Warung Bumdes</span>
                        </li>
                    </ul>
                </div>

            </aside> <!-- end col -->

        </div>
    </div>
</section> <!-- end faq -->
@endsection

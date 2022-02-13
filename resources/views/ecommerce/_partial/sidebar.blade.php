
            <div class="col-lg-4 sidebar">
                <!-- Categories Start -->
                <div class="sidebar-widget category">
                    <h2 class="title">Kategori</h2>
                    <nav class="navbar bg-light">
                        <ul class="navbar-nav">
                            @forelse ($categories as $category)
                                <li class="dropdown">
                                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><span>{{ $category->name }}</span></a>
                                    <ul class="dropdown-menu category">
                                        @foreach ($category->child as $cat)
                                            <li class=""><a class="nav-link" href="{{ route('ecommerce.product.category', [$cat->parent->slug, $cat->slug]) }}">&raquo; {{ $cat->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @empty
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><i class="fas fa-exclamation-triangle"></i> No Categories Found</a>
                                </li>
                            @endforelse
                        </ul>
                    </nav>
                </div>
                <!-- Categories End -->

                <!-- Random Products Start -->
                <div class="sidebar-widget widget-slider">
                    <div class="sidebar-slider normal-slider">
                        @forelse ($randomProducts as $randomProduct)
                            <div class="product-item">
                                <div class="product-title">
                                    <a href="{{ route('ecommerce.product.detail', [$randomProduct->categories->parent->slug, $randomProduct->categories->slug, $randomProduct->slug]) }}">{{substr($randomProduct->name, 0, 60) }} {{ strlen($randomProduct->name) > 60 ? "..." : ""}}</a>
                                </div>
                                <div class="product-image">
                                    <a href="product-detail.html">
                                        <img src="{{ asset($randomProduct->productimages->thumb.$randomProduct->productimages->image1) }}" loading="lazy" alt="{{ $randomProduct->name }}" width="300" height="300">
                                    </a>
                                    @role('customer')
                                        <div class="product-action">
                                            <a href="javascript:void(0);" id="addCartButton" data-product="{{ simple_encrypt($randomProduct->id) }}"><i class="fa fa-cart-plus"></i></a>
                                            <a href="javascript:void(0);" id="addWishlistButton" data-product="{{ simple_encrypt($randomProduct->id) }}"><i class="fa fa-heart"></i></a>
                                        </div>
                                    @endrole
                                </div>
                                <div class="product-price text-center">
                                    <h3>{{ convert_to_rupiah($randomProduct->price) }}</h3>
                                </div>
                            </div>
                        @empty
                            <div class="product-item"><span>No Random Product Available</span></div>
                        @endforelse
                    </div>
                </div>
                <!-- Random Products End -->

                <!-- Brands Start -->
                <div class="sidebar-widget brands overflow-auto">
                    <h2 class="title">Merk Kami</h2>
                    <ul>
                        @forelse ($brands as $brand)
                            <li><a href="{{ route('ecommerce.product.brand', $brand->slug) }}">{{ $brand->name }}</a><span>({{ $brand->products_count }})</span></li>
                        @empty
                            <span>No Brands Available</span>
                        @endforelse
                    </ul>
                </div>
                <!-- Brands End -->

                <div class="sidebar-widget tag">
                <!-- this is empty div -->
                </div>
            </div>

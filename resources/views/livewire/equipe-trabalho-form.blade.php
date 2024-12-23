<div>
    @if(App\Enums\LiberacaoInscricoesEquipeTrabalhoStatusEnum::tryFrom($this->settings['liberacao_inscricoes_equipe_trabalho_status']) == App\Enums\LiberacaoInscricoesEquipeTrabalhoStatusEnum::LIBERADO)
        @if($this->inscrito)
            <form wire:submit.prevent="realizarNovaInscricao" class="md:p-12 mx-4 p-4">
                <section
                    class="bg-transparent text-white min-h-screen flex flex-col justify-center items-center relative">
                    <div class="mx-auto max-w-screen-md text-center lg:px-2 relative">
                        <p class="mt-8 uppercase font-bold text-2xl">Agradecemos a sua inscrição!</p>
                        <div class="flex justify-center">
                            <figure class="flex justify-center items-center mb-4 w-3/5 h-3/5">
                                <img src="{{ asset('img/campista.svg') }}" alt="" class="h-96">
                            </figure>
                        </div>
                        <p class="text-center mx-4 text-yellow-500 text-sm xl:text-xl">
                            <span>Muito obrigado, sua participação não somente ajudará o acampamento de  Famílias, como marcará na vida dos nossos campistas!</span>
                            <br/>
                            <span class="text-white mt-4">Deus abençoe pelo seu sim !</span>
                        </p>
                        <img class="mt-8 mb-8 mx-auto"
                             src="{{ asset('img/qr_code_pix.png') }}?20241021" alt="qrcode pix"
                             width="150"/>
                        <div class="grid justify-items-center mb-4">
                            <button
                                onclick="navigator.clipboard.writeText('00020126580014br.gov.bcb.pix01367f1cd711-a519-424b-a03e-d2faf44a379e5204000053039865802BR5919DIOCESE DE BLUMENAU6010NAVEGANTES62290525CDERT4PSJPM9L1ZMHS7PY2DUS63042DB1')"
                                type="button"
                                class="text-gray-950 text-md bg-gray-200 rounded-sm p-2 flex justify-between">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"/>
                                </svg>
                                Copiar código PIX
                            </button>
                        </div>
                        <p class="text-white text-center text-sm">Favorecido<br> Diocese de Blumenau<br>
                            03.925.280/0035-86 <br>Instituição: CC da Foz do Rio Itajaí Açu</p>
                        <p class="text-white text-center text-2xl mt-8 font-bangers">Valor da Inscrição<br></br>
                            <span class="text-yellow-500 font-bold text-4xl text-center text-md mt-0">R$ 80,00</span><br><br><br>
                            <span class="text-yellow-500 font-bold text-2xl text-center text-md mt-4">POR PESSOA, REALIZAR O PIX COM VALOR TOTAL DA FAMÍLIA</span>
                        </p>
                        <p class="text-center  text-red-600 xl:text-md ">Obrigatório <br> Enviar o  comprovante para o atendente.  </p>
                        <p class="text-white text-center text-sm mt-8">
                            Outras formas de pagamento, falar com atendente
                        </p>
                        <div class="mt-2 grid justify-items-center">
                            <a target="_blank"
                               href="https://wa.me/5547999876256?text=Ol%C3%A1%20segue%20o%20comprovante%20do%20pagamento%20do%20acamapamento%20de%20fam%C3%ADlias"
                               class=" bg-color3 rounded mt-8 mb-8 p-2 w-full  text-white sm:p-4sm:max-w-full
                                   flex items-center justify-center text-[12px] hover:bg-amber-600 hover:font-bold
                                   transition-all duration-500">
                                <span class="relative text-center text-lg text-gray-950">Falar com atendente</span>
                            </a>
                        </div>
                        <p class="text-center  text-red-600 xl:text-md ">Obrigatório <br>levar termo assinado no dia.  </p>
                        <a href="{{ route('pdf.show', ['filename' =>'termo_equipe.pdf'  ]) }}"
                           class=" bg-red-600 rounded mt-8 mb-8 p-2 w-full   sm:p-4sm:max-w-full
                                   flex items-center justify-center text-[12px] hover:bg-amber-600 hover:font-bold
                                   transition-all duration-500 relative text-center text-md text-black font-bold">TERMO RESPONDABILIDADE</a>
                    </div>

                    <button type="submit" role="button"
                            class="bg-color3 rounded mt-8 mb-8 p-2 w-full lg:w-[50%]
                                   flex items-center justify-center text-[12px] hover:bg-amber-600 hover:font-bold
                                   transition-all duration-500">
                        <span class="relative text-center text-lg text-gray-950 font-bold">Nova Inscrição</span>
                    </button>
                </section>
            </form>
        @else
            <form wire:submit.prevent="submitForm" class="md:p-12 mx-4 p-4">
                {{ $this->form }}
                <button type="submit" role="button"
                        class="bg-color1 rounded mt-8 p-2 w-full text-gray-800 sm:p-4 sm:max-w-full flex items-center justify-center hover:text-[20px] min-h-12 max-h-12 transition-all duration-500 text-[18px] hover:bg-[#f6b53c]">
                    <i class="fluentui-document-checkmark-20 text-black mr-2"></i>Inscrever-se
                </button>
            </form>
        @endif
    @elseif(App\Enums\LiberacaoInscricoesEquipeTrabalhoStatusEnum::tryFrom($this->settings['liberacao_inscricoes_equipe_trabalho_status']) == App\Enums\LiberacaoInscricoesEquipeTrabalhoStatusEnum::TRANCADO)
        <section class="bg-transparent text-white min-h-screen flex flex-col justify-center items-center relative">
            <div class="flex justify-center">
                <img src="{{ asset('img/equipe.svg') }}" class="w-full"/>
            </div>
            <div class="text-5xl font-bold text-white uppercase text-center pt-8 pb-8">
                Computando inscrições...
            </div>
        </section>

    @elseif(App\Enums\LiberacaoInscricoesEquipeTrabalhoStatusEnum::tryFrom($this->settings['liberacao_inscricoes_equipe_trabalho_status']) == App\Enums\LiberacaoInscricoesEquipeTrabalhoStatusEnum::ENCERRADO)
        <section class="text-white min-h-screen flex flex-col justify-center items-center relative">
            <div id="particles-js2" class="min-h-screen"></div>
            <div class="mx-auto max-w-screen-md text-center lg:px-2 relative">
                <div class="lg:mb-64">
                    <p class="uppercase font-bold text-2xl lg:text-4xl text-amber-500 mb-0">Inscrições para a Equipe de Trabalho Encerradas!</p>
                </div>

                <div class="flex justify-center">
                    <figure class="flex justify-center items-center mt-20 lg:mt-0 lg:mb-12 rounded">
                        <img src="{{ asset('img/Campfire-bro.svg') }}" alt=""
                             class="w-full h-96 rounded-2xl">
                    </figure>
                </div>
            </div>
        </section>
    @endif
</div>

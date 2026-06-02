<?php

namespace App\Services;

class StoryGenerator
{
    public function __construct()
    {
        // No longer requires HuggingFace or Pollinations keys
    }

    private function getInitialPlot($genre, $location)
    {
        if ($genre === 'Horror' && $location === 'Pendakian') {
            return [
                'content' => "Malam turun saat kamu, Nana, dan Beni tiba di jalur pendakian Gunung Panggung. Kabut menelan pepohonan, api unggun mulai redup, lalu terdengar bisikan tipis dari balik semak, memanggil namamu seolah sudah lama menunggu. Nana menggenggam lenganmu, wajahnya pucat. Beni menyorotkan senter, tapi cahaya hanya memantul pada batang-batang basah. Bisikan itu makin dekat, lembut sekaligus memaksa, seperti janji keselamatan dari tempat yang salah. Di antara napas kalian yang tertahan, gunung terasa ikut mendengar. Apa yang akan kamu lakukan?",
                'choices' => [
                    ['choice_text' => 'Ikuti bisikan'],
                    ['choice_text' => 'Abaikan bisikan']
                ],
                'image' => 'Horror/Pendakian/intro awal.png'
            ];
        }

        if ($genre === 'Horror' && $location === 'Rumah Sakit') {
            return [
                'content' => "Kamu berdiri sendirian di depan gerbang berkarat sebuah rumah sakit tua yang sudah ditutup puluhan tahun lalu. Gedung besar itu tampak gelap dan sangat suram. Jantungmu berdebar kencang, tapi rasa penasaran mengalahkan rasa takutmu.",
                'choices' => [
                    ['choice_text' => 'Masuk ke dalam gedung']
                ]
            ];
        }

        if ($genre === 'Adventure' && $location === 'Pulau Terpencil') {
            return [
                'content' => "Kamu sedang santai berlayar menggunakan perahu kecil untuk liburan. Tiba-tiba, langit berubah hitam pekat dan badai besar datang! Ombak raksasa mulai menghantam perahumu dengan sangat keras dari berbagai arah.",
                'choices' => [
                    ['choice_text' => 'Berpegangan sekuat tenaga']
                ]
            ];
        }

        if ($genre === 'Adventure' && $location === 'Gua Misterius') {
            return [
                'content' => "Kamu, Nana, dan Beni menyusuri Gua Misterius ketika cahaya senter menangkap pintu bunker berkarat di balik stalaktit basah. Engselnya menjerit saat Beni mendorongnya, lalu udara dingin dan lembap menyembur dari dalam. Nana berbisik agar kalian hati-hati, tapi rasa penasaran membuatmu melangkah masuk. Lorong bunker gelap membelah ke dua arah, dindingnya penuh bekas goresan dan pipa tua yang menetes pelan. Dari kejauhan terdengar bunyi logam dipukul, sekali, lalu hilang. Kalian berdiri di persimpangan pertama. Jalur mana yang kamu pilih?",
                'choices' => [
                    ['choice_text' => 'Pilih jalur kiri'],
                    ['choice_text' => 'Pilih jalur kanan']
                ]
            ];
        }

        return [
            'content' => "Kamu berdiri di awal sebuah petualangan seru yang baru dan belum diketahui.",
            'choices' => [
                ['choice_text' => 'Mulai perjalanan']
            ]
        ];
    }

    private function getStoryNodes($genre, $location)
    {
        if ($genre === 'Horror' && $location === 'Pendakian') {
            return [
                'Ikuti bisikan' => [
                    'content' => "Kamu melangkah menjauh dari kehangatan api unggun, mengikuti suara bisikan gaib yang memanggil namamu di tengah kabut dingin Gunung Panggung. Langkahmu goyah, terhipnotis oleh suara halus yang terdengar seperti jeritan keputusasaan. Tiba-tiba, tanah di bawah kakimu runtuh. Kamu jatuh ke dalam jurang yang curam! Terhempas keras ke bebatuan tajam, pandanganmu perlahan menjadi gelap. Ketika matamu perlahan terbuka, kepalamu terasa pening dan darah mengalir dari luka parah di pelipismu. Nana dan Beni tidak terlihat di mana pun. Kamu sendirian, tersesat di dasar jurang dengan kelaparan dan kehausan yang menyiksa. Di dekat kakimu, di bawah semak berduri, kamu menemukan sebuah ransel pendaki tua berwarna hitam yang sepertinya ditinggalkan pemiliknya.",
                    'choices' => [
                        ['choice_text' => 'Gunakan persediaan'],
                        ['choice_text' => 'Tidak menggunakan persediaan']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 1 ikuti bisikan.png'
                ],
                'Gunakan persediaan' => [
                    'content' => "Dengan tangan gemetar karena luka parah, kamu merangkak mendekati ransel hitam tersebut dan membukanya. Di dalamnya, kamu menemukan beberapa bungkus biskuit kering dan sebotol air mineral yang masih tersegel bersih. Tanpa berpikir panjang, kamu segera memakan dan meminum persediaan tersebut. Rasa lapar dan haus yang menyiksa perlahan mereda, memberikan sedikit tenaga baru ke dalam tubuhmu yang lemas. Kamu mencoba berdiri, meskipun luka di kakimu masih berdenyut perih. Di sekelilingmu, pepohonan hutan yang tinggi tampak seperti bayangan raksasa yang mengintip dalam kegelapan. Kini kamu dihadapkan pada dua pilihan sulit: memaksakan diri melanjutkan perjalanan mencari jalan keluar, atau berdiam diri di tempat ini sambil berharap ada bantuan yang datang.",
                    'choices' => [
                        ['choice_text' => 'Lanjut perjalanan'],
                        ['choice_text' => 'Menunggu bantuan']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 1.1 mati memakan persediaan.png'
                ],
                'Lanjut perjalanan' => [
                    'content' => "Kamu menolak menyerah pada rasa sakit. Dengan bertumpu pada sebilah dahan kayu kering, kamu menyeret kakimu yang terluka parah untuk melanjutkan perjalanan menembus rimbunnya hutan Gunung Panggung. Setiap langkah terasa seperti duri yang menusuk tulang, namun tekadmu untuk hidup mengalahkan segalanya. Setelah berjalan berjam-jam di bawah guyuran kabut tebal, terdengar suara langkah kaki yang mendekat dan kilatan lampu senter. \"Ada orang di sana!\" teriak sebuah suara. Itu adalah tim SAR yang sedang mencarimu bersama Nana dan Beni! Kamu akhirnya diselamatkan dengan luka fisik yang parah, namun selamat dari teror gunung tersebut. (ENDING 1)",
                    'choices' => [],
                    'is_ending' => true,
                    'image' => 'Horror/Pendakian/alur 1.2 murid petapa.png'
                ],
                'Menunggu bantuan' => [
                    'content' => "Kamu memutuskan untuk tetap diam di dasar jurang, bersandar pada sebongkah batu besar yang dingin sambil memeluk ransel. Waktu berlalu sangat lambat, dan udara malam Gunung Panggung berubah menjadi sangat beku. Harapanmu untuk kedatangan tim penyelamat perlahan memudar. Luka parah di kakimu mulai membengkak dan berubah warna menjadi keunguan—infeksi bakteri telah menyebar dengan sangat cepat ke seluruh tubuhmu. Kesadaranmu perlahan-lahan mulai memudar seiring dengan meningkatnya rasa demam yang membakar. Di bawah tatapan sunyi pepohonan malam, kamu menghembuskan napas terakhirmu sebelum pertolongan sempat tiba. (ENDING 2)",
                    'choices' => [],
                    'is_ending' => true,
                    'image' => 'Horror/Pendakian/alur 1.2 meninggal akibat kelelahan.png'
                ],
                'Tidak menggunakan persediaan' => [
                    'content' => "Meskipun perutmu terasa sangat perih dan tenggorokanmu kering bagai padang pasir, rasa curiga membuatmu membiarkan ransel misterius itu tetap tertutup. Kamu memilih untuk terus berjalan dengan merangkak dan menyeret tubuhmu di tanah yang lembap, mencari jalan keluar dari jurang ini. Kelelahan yang luar biasa terus meningkat, membuat napasmu terengah-engah dan pandanganmu berkunang-kunang. Tiba-tiba, di balik rimbunnya tanaman menjalar di dinding jurang, kamu melihat sebuah celah sempit yang mengarah ke sebuah gua tersembunyi yang sangat gelap. Apakah kamu akan memberanikan diri masuk ke dalam gua tersebut untuk berlindung, atau terus melewatinya dan mencari jalan setapak yang lain?",
                    'choices' => [
                        ['choice_text' => 'Masuk ke gua'],
                        ['choice_text' => 'Lewati gua']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 1.2 menemukan goa.png'
                ],
                'Masuk ke gua' => [
                    'content' => "Kamu merangkak masuk ke dalam celah gua yang sempit. Di dalam, udara terasa sangat hangat dan berbau wewangian dupa kuno. Di tengah gua, duduk seorang pertapa tua berpakaian putih kumal dengan jenggot panjang yang sedang bermeditasi di atas batu datar. Ketika dia membuka matanya yang bercahaya keemasan, rasa takutmu seketika sirna. Pertapa gaib itu tersenyum dan berkata bahwa kamu terpilih untuk mewarisi pengetahuan kuno Gunung Panggung. Selama berbulan-bulan yang ajaib, kamu dilatih ilmu gaib dan meditasi spiritual, hingga akhirnya kamu diangkat menjadi murid spiritual sang pertapa dan hidup abadi di dunia gaib. (ENDING 3)",
                    'choices' => [],
                    'is_ending' => true,
                    'image' => 'Horror/Pendakian/alur 1.2 murid petapa.png'
                ],
                'Lewati gua' => [
                    'content' => "Kamu memilih mengabaikan gua misterius tersebut dan terus menyeret tubuhmu menyusuri dinding jurang yang dingin. Namun, keputusan itu harus dibayar mahal. Tanpa makanan dan minuman, tubuhmu yang terluka parah tidak lagi memiliki energi yang tersisa. Setiap sel di tubuhmu berteriak meminta istirahat. Langkahmu terhenti di bawah sebuah pohon tua yang rindang. Pandanganmu mengabur menjadi kegelapan total saat kelelahan ekstrem akhirnya merenggut nyawamu. Tubuhmu menyatu dengan keheningan hutan malam Gunung Panggung yang kejam. (ENDING 4)",
                    'choices' => [],
                    'is_ending' => true,
                    'image' => 'Horror/Pendakian/alur 1.2 meninggal akibat kelelahan.png'
                ],
                'Abaikan bisikan' => [
                    'content' => "Kamu menggelengkan kepala dengan kuat, menolak untuk mendengarkan bisikan mistis yang memanggil namamu. \"Abaikan saja suara itu, Nana, Beni. Itu hanya angin gunung,\" katamu dengan tegas. Dengan tekad bulat, kalian bertiga merapatkan barisan dan terus berjalan menyusuri jalan setapak yang menanjak. Tak lama kemudian, kabut tebal perlahan terbelah, memperlihatkan sebuah pohon beringin raksasa yang sangat tua di tengah persimpangan. Di bawah akar pohon yang menjulur seperti jemari tangan raksasa, terdapat sebuah peti kayu kuno yang terikat rantai besi berkarat dengan gembok tembaga yang kokoh. Rasa penasaran mulai menyelimuti kalian. Apakah kalian akan membuka peti itu, atau mengabaikannya dan terus berjalan?",
                    'choices' => [
                        ['choice_text' => 'Abaikan peti'],
                        ['choice_text' => 'Buka peti']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 2 kotak peti.png'
                ],
                'Abaikan peti' => [
                    'content' => "\"Jangan menyentuh barang-barang aneh di gunung ini,\" Beni memperingatkan dengan nada cemas. Setuju dengan ucapannya, kamu memutuskan untuk mengabaikan peti kuno tersebut dan melangkah pergi. Kalian bertiga melanjutkan perjalanan menembus kegelapan malam yang semakin larut. Setelah berjalan cukup jauh, kalian tiba di sebuah persimpangan jalan yang sangat membingungkan. Jalan setapak itu terbagi menjadi dua arah: Jalan 1 yang tampak menurun curam dengan pepohonan yang rapat, dan Jalan 2 yang mendatar namun diselimuti oleh kabut putih yang sangat tebal. Takdir mana yang akan kalian pilih?",
                    'choices' => [
                        ['choice_text' => 'Pilih jalan 1'],
                        ['choice_text' => 'Pilih jalan 2']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 2.1 tidak membuka peti dan bertemu percabangan jalan.png'
                ],
                'Pilih jalan 1' => [
                    'content' => "Kalian mengambil Jalan 1, melangkah menuruni turunan curam. Tiba-tiba, kabut menghilang dan di depan kalian terbentang sebuah pasar tradisional yang sangat ramai dengan lampion merah menyala di mana-mana. Namun, ada yang aneh—para penjual dan pembeli mengenakan pakaian adat kuno dan wajah mereka pucat tanpa ekspresi. Sebelum kalian sempat melarikan diri, sesosok wanita cantik bermahkota ular—Badarawuhi—muncul dari kegelapan dan menatap kalian dengan senyum memikat. Jiwamu seketika terikat oleh mantra gaibnya, dan kamu berakhir menjadi penari gaib pengikut Badarawuhi di pasar gaib tersebut untuk selamanya. (ENDING 5)",
                    'choices' => [],
                    'is_ending' => true,
                    'image' => 'Horror/Pendakian/alur 2.1 ending badarawuhi.png'
                ],
                'Pilih jalan 2' => [
                    'content' => "Kalian memilih Jalan 2, menembus kabut putih yang tebal. Di ujung jalan, kalian dikejutkan oleh pemandangan sebuah istana megah bernuansa emas hitam yang berdiri kokoh di puncak bukit gaib. Puluhan dayang gaib menyambut kalian dengan membungkuk hormat, menuntun kalian masuk ke dalam aula utama untuk melakukan sebuah ritual misterius dengan sesajen bunga tujuh rupa. Tanpa sadar, mahkota emas diletakkan di atas kepalamu. Kamu telah dipilih oleh entitas penguasa gunung untuk dinobatkan sebagai Permaisuri gaib mereka, hidup bergelimang kemewahan mistis di istana tersebut selamanya. (ENDING 6)",
                    'choices' => [],
                    'is_ending' => true,
                    'image' => 'Horror/Pendakian/alur 2.1 ending permaisuri.png'
                ],
                'Buka peti' => [
                    'content' => "Rasa penasaran yang besar membuatmu mengabaikan peringatan Beni. Dengan menggunakan sebuah batu tajam yang besar, kamu memukul rantai besi berkarat itu berkali-kali hingga gemboknya hancur terbuka. Tutup peti berderit pelan saat dibuka, mengeluarkan aroma harum kayu cendana kuno yang sangat pekat. Di dalam peti yang dilapisi kain beludru merah kusam, berbaring sebuah keris pusaka berlapis emas yang memancarkan pendaran cahaya keemasan mistis. Gagasannya terasa sangat kuat, seolah-olah pusaka tersebut memiliki nyawa dan memanggil tanganmu untuk menggenggamnya.",
                    'choices' => [
                        ['choice_text' => 'Ambil keris'],
                        ['choice_text' => 'Tinggalkan keris']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 2.2 membuka kotak peti.png'
                ],
                'Ambil keris' => [
                    'content' => "Kamu mengulurkan tangan dan menggenggam gagang keris pusaka tersebut. Seketika, getaran energi dingin menyengat lenganmu hingga ke jantung! Mata batinmu langsung terbuka secara paksa, memperlihatkan dimensi gaib Gunung Panggung yang dipenuhi bayangan hitam mengerikan. Di tengah perjalanan, udara mendadak membeku dan dari balik semak-semak, sesosok hantu raksasa bermata merah menyala dengan taring panjang melompat menghadang kalian! Nana dan Beni berteriak ketakutan dan bersembunyi di belakangmu. Apakah kamu akan menggunakan keris tersebut untuk melawan hantu itu, atau memilih untuk lari menyelamatkan diri?",
                    'choices' => [
                        ['choice_text' => 'Lawan Hantu'],
                        ['choice_text' => 'Kabur dari hantu']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 2.2.1.2 dihadang oleh hantu dan memegang keris.png'
                ],
                'Lawan Hantu' => [
                    'content' => "Dengan sisa keberanian, kamu mengangkat keris pusaka saat hantu itu menerjang. Nana menahan tangis di belakangmu, sementara Beni menyorotkan senter tepat ke wajah makhluk itu. Bilah keris memancarkan cahaya pucat, membelah kabut hitam yang mengurung jalan. Hantu itu menjerit, mundur, lalu lenyap menjadi abu dingin di antara pepohonan. Untuk pertama kalinya, gunung kembali sunyi tanpa bisikan. Kamu membawa Nana dan Beni turun dengan selamat. Meski tubuh gemetar, hatimu akhirnya tenang. (ENDING 7)",
                    'choices' => [],
                    'is_ending' => true,
                    'image' => 'Horror/Pendakian/alur 2.2.1.2.4 ending disegani.png'
                ],
                'Kabur dari hantu' => [
                    'content' => "Rasa takut mengalahkan keberanianmu. Kamu memasukkan kembali keris itu ke dalam saku dan berteriak, \"Lari!\" Kalian bertiga berlari kencang menerobos ranting-ranting pohon yang tajam di bawah kejaran jeritan hantu raksasa yang murka. Setelah berlari tanpa arah hingga napas tersengal-sengal, kalian akhirnya berhasil mencapai gerbang desa di kaki gunung saat fajar menyingsing. Hantu tersebut tidak bisa mengejar kalian ke dunia manusia. Kamu selamat dan berhasil pulang bersama Nana dan Beni, namun trauma mendalam dan bayangan hantu tersebut akan terus menghantui mimpimu seumur hidup. (ENDING 8)",
                    'choices' => [],
                    'is_ending' => true,
                    'image' => 'Horror/Pendakian/alur 2.2.1.2.3 ending ketakutan seumur hidup.png'
                ],
                'Tinggalkan keris' => [
                    'content' => "Kamu memutuskan untuk tidak mengambil risiko dan meninggalkan keris misterius tersebut di dalam petinya. Kamu menutup kembali peti kayu itu dan berjalan melanjutkan perjalanan bersama Nana dan Beni. Namun, sepanjang perjalanan menembus rimbunnya hutan, pikiranmu tidak bisa tenang. Rasa penasaran yang sangat besar terus berputar di kepalamu, disertai perasaan aneh bahwa kamu telah melewatkan takdir yang besar. Langkahmu terasa berat di persimpangan jalan setapak yang gelap. Apakah kamu akan terus melangkah maju tanpa kembali, atau berbalik arah untuk mengambil keris pusaka itu?",
                    'choices' => [
                        ['choice_text' => 'Tidak kembali ambil keris'],
                        ['choice_text' => 'Kembali ambil keris']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 2.2.1 kebingungan mengambil keris atau tidak.png'
                ],
                'Tidak kembali ambil keris' => [
                    'content' => "Kamu menepis rasa penasaranmu dan memilih untuk tetap melanjutkan perjalanan ke depan bersama Nana dan Beni. Pikiranmu perlahan-lahan fokus kembali pada jalan setapak di hadapan kalian. Setelah berjalan menuruni bukit berbatu selama beberapa puluh menit, kalian tiba di sebuah persimpangan jalan bercabang yang sangat sunyi dan misterius. Takdir menantimu sekali lagi di sini. Arah manakah yang akan kalian ambil untuk keluar dari hutan Gunung Panggung ini?",
                    'choices' => [
                        ['choice_text' => 'Pilih jalan 1'],
                        ['choice_text' => 'Pilih jalan 2']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 2.1 tidak membuka peti dan bertemu percabangan jalan.png'
                ],
                'Kembali ambil keris' => [
                    'content' => "Rasa penasaran yang tak tertahankan akhirnya membuatmu berbalik arah. \"Tunggu sebentar di sini, aku harus mengambil sesuatu!\" serumu kepada Nana dan Beni yang kebingungan. Kamu berlari kencang kembali ke pohon beringin raksasa, membuka peti, dan mengambil keris pusaka tersebut. Seketika, matamu terbelalak—mata batinmu terbuka paksa dan di tengah jalan kembali, sesosok hantu tinggi besar dengan wajah menyeramkan melompat menghadang langkahmu dengan geraman yang menggetarkan tanah! Apakah kamu akan melawan hantu itu dengan keris pusaka, atau kabur dari hadapannya?",
                    'choices' => [
                        ['choice_text' => 'Lawan Hantu'],
                        ['choice_text' => 'Kabur dari hantu']
                    ],
                    'is_ending' => false,
                    'image' => 'Horror/Pendakian/alur 2.2.1.2 dihadang oleh hantu dan memegang keris.png'
                ]
            ];
        }

        if ($genre === 'Horror' && $location === 'Rumah Sakit') {
            return [
                'Masuk ke dalam gedung' => [
                    'content' => "Begitu masuk, pintu di belakangmu terbanting keras dan terkunci sendiri! Kamu kini berada di lorong berdebu. Lampu neon di atap berkedip redup. Dari kejauhan, terdengar suara tangisan pelan.",
                    'choices' => [
                        ['choice_text' => 'Cari sumber suara'],
                        ['choice_text' => 'Sembunyi di ruang perawat']
                    ],
                    'is_ending' => false
                ],
                'Cari sumber suara' => [
                    'content' => "Kamu pelan-pelan mengikuti suara tangisan itu. Ternyata suaranya dari dalam ruangan kamar mayat. Pintunya sedikit terbuka dan hawanya sangat dingin.",
                    'choices' => [
                        ['choice_text' => 'Masuk kamar mayat'],
                        ['choice_text' => 'Lari ke tangga']
                    ],
                    'is_ending' => false
                ],
                'Sembunyi di ruang perawat' => [
                    'content' => "Kamu masuk ke ruangan dokter dan langsung mengunci pintunya. Di meja kerja ada lampu senter yang masih menyala dan kotak P3K.",
                    'choices' => [
                        ['choice_text' => 'Nyalakan senter'],
                        ['choice_text' => 'Buka kotak obat']
                    ],
                    'is_ending' => false
                ],
                'Masuk kamar mayat' => [
                    'content' => "Kamu nekat masuk. Ada seorang suster berbaju kotor sedang membelakangimu. Waktu dia menoleh, ternyata wajahnya rata tanpa mata dan mulut!",
                    'choices' => [
                        ['choice_text' => 'Pura-pura mati'],
                        ['choice_text' => 'Lawan suster itu']
                    ],
                    'is_ending' => false
                ],
                'Lari ke tangga' => [
                    'content' => "Kamu ketakutan dan lari mencari tangga darurat. Sayangnya tangganya sangat panjang dan seperti tidak berujung.",
                    'choices' => [
                        ['choice_text' => 'Terus lari ke bawah'],
                        ['choice_text' => 'Istirahat di anak tangga']
                    ],
                    'is_ending' => false
                ],
                'Nyalakan senter' => [
                    'content' => "Kamu menyalakan senter ke arah sudut ruangan. Tiba-tiba hantu perawat melompat ke arahmu. Layar menjadi gelap dan kamu menghilang selamanya.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Buka kotak obat' => [
                    'content' => "Kamu meminum pil penenang dari kotak itu. Pandanganmu memudar, lalu kamu terbangun di kasur rumahmu sendiri. Puji Tuhan, semua cuma mimpi buruk!",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Pura-pura mati' => [
                    'content' => "Kamu menahan napas sambil tiduran. Hantu itu kira kamu sudah meninggal, lalu dia menutup tubuhmu pakai kain putih untuk selamanya.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Lawan suster itu' => [
                    'content' => "Kamu melempar botol sirup ke muka hantu itu lalu lari kencang keluar pintu depan. Hore! Kamu selamat dari rumah sakit seram itu.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Terus lari ke bawah' => [
                    'content' => "Kamu lari sangat lama sampai kakimu sakit sekali, tapi tak kunjung keluar gedung. Kamu terjebak masuk ke dimensi gaib tak berujung.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Istirahat di anak tangga' => [
                    'content' => "Waktu kamu duduk istirahat, ada kakek satpam baik hati yang menunjukkan pintu jalan keluar rahasia. Akhirnya kamu selamat bisa pulang ke rumah.",
                    'choices' => [],
                    'is_ending' => true
                ]
            ];
        }

        if ($genre === 'Adventure' && $location === 'Pulau Terpencil') {
            return [
                'Berpegangan sekuat tenaga' => [
                    'content' => "BRUK! Perahumu terbalik dihantam ombak. Setelah sempat pingsan, kamu terbangun dan mendapati dirimu terdampar di pasir pulau. Di depanmu ada hutan lebat.",
                    'choices' => [
                        ['choice_text' => 'Masuk ke dalam hutan'],
                        ['choice_text' => 'Susuri pinggiran pantai']
                    ],
                    'is_ending' => false
                ],
                'Masuk ke dalam hutan' => [
                    'content' => "Hutannya dipenuhi pohon-pohon besar. Di sana kamu menemukan buah beri berwarna biru bercahaya yang kelihatannya enak sekali. Kebetulan perutmu sangat lapar.",
                    'choices' => [
                        ['choice_text' => 'Makan buah itu'],
                        ['choice_text' => 'Lanjut cari air minum']
                    ],
                    'is_ending' => false
                ],
                'Susuri pinggiran pantai' => [
                    'content' => "Kamu jalan pelan-pelan di pinggir pantai berbatu. Matamu tertuju pada sebuah kotak harta karun yang terkubur separuh di dalam pasir.",
                    'choices' => [
                        ['choice_text' => 'Paksa buka kotak'],
                        ['choice_text' => 'Biarkan saja kotak itu']
                    ],
                    'is_ending' => false
                ],
                'Makan buah itu' => [
                    'content' => "Rasanya sangat manis! Hebatnya lagi, buah biru itu memberimu kekuatan sihir super. Badanmu jadi enteng dan kamu bisa melompat setinggi pohon.",
                    'choices' => [
                        ['choice_text' => 'Lompat ke seberang laut'],
                        ['choice_text' => 'Buat istana kayu']
                    ],
                    'is_ending' => false
                ],
                'Lanjut cari air minum' => [
                    'content' => "Kamu mendengar suara gemercik air terjun. Sayangnya, ada buaya raksasa yang sedang tidur pulas menutupi jalan ke arah air tersebut.",
                    'choices' => [
                        ['choice_text' => 'Ambil air diam-diam'],
                        ['choice_text' => 'Lawan buaya pakai kayu']
                    ],
                    'is_ending' => false
                ],
                'Paksa buka kotak' => [
                    'content' => "Kunci kotak itu berhasil kamu hancurkan pakai batu karang. Wow! Isinya ada pistol kembang api penyelamat dan sebuah peta harta bajak laut.",
                    'choices' => [
                        ['choice_text' => 'Tembakkan suar ke udara'],
                        ['choice_text' => 'Ikuti peta harta karun']
                    ],
                    'is_ending' => false
                ],
                'Biarkan saja kotak itu' => [
                    'content' => "Kamu membiarkan kotak itu dan jalan terus seharian penuh. Sayangnya kamu kelelahan dan pingsan dehidrasi di pasir pantai. Petualangan usai.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Lompat ke seberang laut' => [
                    'content' => "Dengan tenaga ajaib itu, kamu berhasil melompat-lompat menyeberangi lautan menuju kota terdekat dan diangkat menjadi superhero baru!",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Buat istana kayu' => [
                    'content' => "Karena betah, kamu membangun rumah pohon raksasa. Kamu hidup damai dan sehat menjadi penjaga hutan di pulau indah tersebut.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Ambil air diam-diam' => [
                    'content' => "Kamu mengendap-endap dan sukses minum air segar tanpa ketahuan buaya. Waktu sedang minum, kapal tim penyelamat tak sengaja melihatmu. Kamu diselamatkan!",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Lawan buaya pakai kayu' => [
                    'content' => "Buaya itu malah terbangun marah! Karena hanya bermodal sebatang kayu, kamu tak bisa menang dan terpaksa jadi makan siangnya.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Tembakkan suar ke udara' => [
                    'content' => "DOR! Cahaya merah meluncur ke langit. Pasukan Angkatan Laut yang sedang patroli melihatnya dan segera menjemputmu dengan kapal besar.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Ikuti peta harta karun' => [
                    'content' => "Peta itu membawamu masuk ke gua yang penuh dengan emas berlian. Kamu bukan hanya selamat, tapi pulang sebagai orang paling kaya di kotamu!",
                    'choices' => [],
                    'is_ending' => true
                ]
            ];
        }

        if ($genre === 'Adventure' && $location === 'Gua Misterius') {
            return [
                'Pilih jalur kiri' => [
                    'content' => "Kamu memilih jalur kiri. Nana mengikuti dari belakang, sementara Beni menjaga senter tetap mengarah ke depan. Lorong itu berakhir di ruang kontrol berdebu, penuh tombol mati dan layar retak. Di atas meja, sebuah radio tua tiba-tiba menyala sendiri. Suaranya pecah, berat, dan seperti datang dari dasar bunker. \"Tolong... jangan biarkan kami di bawah.\" Nana menatapmu tegang. Beni menggeleng, yakin itu jebakan. Radio kembali berderak, kali ini menyebut namamu. Apa yang akan kamu lakukan?",
                    'choices' => [
                        ['choice_text' => 'Jawab radio'],
                        ['choice_text' => 'Matikan radio']
                    ],
                    'is_ending' => false
                ],
                'Jawab radio' => [
                    'content' => "Kamu menekan tombol bicara. Suara di radio langsung memandu kalian menuju tangga besi yang turun ke ruang bawah. Nana membaca angka-angka di dinding, Beni memastikan pintu di belakang masih terbuka. Dari bawah, terdengar rintihan minta tolong, lemah tapi nyata. Instruksi radio meminta kalian turun sekarang sebelum sistem bunker terkunci. Udara makin dingin, dan lampu darurat berkedip merah. Nana ingin menolong. Beni takut kalian sedang dipancing masuk lebih dalam. Keputusan ada padamu.",
                    'choices' => [
                        ['choice_text' => 'Turun menolong'],
                        ['choice_text' => 'Kabur']
                    ],
                    'is_ending' => false
                ],
                'Turun menolong' => [
                    'content' => "Kamu turun bersama Nana dan Beni melewati anak tangga licin. Di ruang bawah, kalian menemukan penjaga tua terluka, terjepit rak besi yang roboh. Beni mengangkat rak itu sekuat tenaga, Nana membalut lukanya, dan kamu menuntunnya menuju pintu darurat. Radio tua kembali berbunyi, kali ini hanya berisi ucapan terima kasih. Penjaga itu membuka panel rahasia menuju mulut gua. Kalian semua keluar saat fajar menyentuh batu basah. Petualangan berakhir dengan semua selamat. (ENDING 1)",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Kabur' => [
                    'content' => "Kamu memilih kabur. Nana dan Beni berlari mengikutimu melewati ruang kontrol, tapi bunker seperti berubah bentuk. Pintu berkarat yang tadi terbuka kini menutup otomatis dengan dentuman keras. Beni mencoba mencongkelnya, Nana memanggil bantuan, namun radio hanya memutar tawa statis. Lampu mati satu per satu, menyisakan gelap yang makin rapat. Dari bawah, suara langkah naik perlahan. Kalian terjebak di dalam bunker, dan tidak ada yang tahu pintu itu pernah ditemukan. (ENDING 2)",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Matikan radio' => [
                    'content' => "Kamu memutar tombol radio sampai suaranya mati. Sesaat bunker hening, lalu seluruh lampu padam mendadak. Nana menarik napas panik. Beni menyorotkan senter ke ujung ruangan, tepat saat deretan robot penjaga tua aktif, mata sensornya menyala merah. Roda besi mereka bergerak, pelan tapi pasti, mengepung kalian dari tiga sisi. Tanpa radio, tidak ada instruksi, tidak ada pintu darurat yang terbuka. Jerit kalian tenggelam di ruang kontrol berdebu. Petualangan berakhir di tangan mesin penjaga. (ENDING 3)",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Pilih jalur kanan' => [
                    'content' => "Kamu memilih jalur kanan. Lorongnya lebih sempit dan berbau besi tua. Nana menunjuk rak-rak runtuh berisi senjata berkarat, sementara Beni menemukan peti rahasia di sudut gudang. Ukiran di tutupnya tertutup debu, tetapi kuncinya sudah retak. Sebelum kalian sempat memutuskan, terdengar suara langkah berat mendekat dari lorong belakang. Gudang terasa makin sesak. Peti itu mungkin menyimpan alat untuk bertahan, atau bahaya yang lebih buruk. Apa yang akan kamu lakukan?",
                    'choices' => [
                        ['choice_text' => 'Buka peti'],
                        ['choice_text' => 'Lanjutkan perjalanan']
                    ],
                    'is_ending' => false
                ],
                'Buka peti' => [
                    'content' => "Kamu membuka peti rahasia itu. Engselnya berderit, lalu gas beracun hijau pucat menyembur keluar. Nana menutup hidungnya dengan syal, Beni menarikmu mundur, tapi di dalam peti terlihat gulungan logam dan map tua. Langkah dari lorong semakin dekat. Matamu perih, napasmu mulai berat, dan waktu untuk berpikir hampir habis. Peti itu bisa ditutup agar kalian selamat, atau kamu bisa nekat mengambil isinya sebelum gas memenuhi gudang.",
                    'choices' => [
                        ['choice_text' => 'Tutup peti'],
                        ['choice_text' => 'Ambil isi']
                    ],
                    'is_ending' => false
                ],
                'Tutup peti' => [
                    'content' => "Kamu membanting tutup peti sebelum gas memenuhi paru-paru kalian. Beni menyeret Nana keluar gudang, lalu kalian kabur melewati lorong sempit sampai udara gua kembali terasa segar. Dari jauh, suara langkah berhenti tepat di depan peti itu. Kalian selamat, tetapi rasa penasaran menggantung seperti kabut. Apa isi peti itu? Siapa yang menjaganya? Pertanyaan itu terus mengikuti kalian sampai keluar dari Gua Misterius. (ENDING 4)",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Ambil isi' => [
                    'content' => "Kamu menahan napas dan meraih isi peti. Nana membuka jalan, Beni menarikmu keluar sebelum gas menelan gudang. Di tanganmu ada dokumen rahasia berstempel pemerintah lama, berisi peta bunker, catatan eksperimen, dan lokasi ruang tersembunyi. Saat kalian menyerahkannya ke pihak berwenang, penemuan itu menggemparkan banyak orang. Nama kamu, Nana, dan Beni dikenal sebagai penjelajah yang membongkar misteri besar di balik gua itu. (ENDING 5)",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Lanjutkan perjalanan' => [
                    'content' => "Kamu memilih meninggalkan peti dan melanjutkan perjalanan. Nana menyapu dinding dengan senter, menemukan peta kuno yang digores langsung pada batu. Beni mengikuti garisnya hingga menemukan terowongan rahasia tersembunyi di balik rak besi. Dari dalamnya mengalir udara hangat dan aroma tanah basah. Peta itu menunjukkan simbol harta, tetapi juga tanda bahaya di ujung jalur. Kalian bisa masuk ke terowongan itu, atau kembali untuk melapor sebelum semuanya makin berisiko.",
                    'choices' => [
                        ['choice_text' => 'Masuk terowongan'],
                        ['choice_text' => 'Kembali']
                    ],
                    'is_ending' => false
                ],
                'Masuk terowongan' => [
                    'content' => "Kamu masuk ke terowongan rahasia bersama Nana dan Beni. Jalurnya panjang, sempit, dan dipenuhi akar yang menembus langit-langit batu. Di ujungnya, kalian menemukan ruangan bundar berisi peti harta karun tua: emas, batu mulia, dan artefak yang belum pernah tercatat. Beni tertawa tidak percaya, Nana memotret semuanya sebagai bukti. Setelah dilaporkan resmi, kalian mendapat penghargaan dan bagian penemuan. Hidupmu berubah menjadi kaya dari misteri gua itu. (ENDING 6)",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Kembali' => [
                    'content' => "Kamu memilih kembali. Nana setuju karena peta kuno itu terlalu berbahaya untuk dijelajahi tanpa persiapan, dan Beni menandai jalur keluar agar tidak tersesat. Setelah keluar dari gua, kalian melapor ke pemerintah tentang bunker, gudang, dan terowongan rahasia. Penyelidikan besar pun dimulai. Karena kamu, Nana, dan Beni paling memahami jalurnya, kalian diminta menjadi pemandu resmi ekspedisi Gua Misterius. (ENDING 7)",
                    'choices' => [],
                    'is_ending' => true
                ]
            ];
        }

        return [];
    }

    public function generateInitialStory($userName, $gender, $genre, $location)
    {
        $initialData = $this->getInitialPlot($genre, $location);
        
        $aiContent = $this->expandWithAI($initialData['content'], [
            'userName' => $userName,
            'gender' => $gender,
            'genre' => $genre,
            'location' => $location
        ]);

        return [
            'content' => $aiContent,
            'choices' => $initialData['choices'],
            'is_ending' => false,
            'image' => $initialData['image'] ?? 'wallpaperhorror.jpeg'
        ];
    }

    public function generateNextSegment($previousStory, $choiceText, $currentStep, $maxSteps = 5, $genre = 'Horror', $location = 'Pendakian')
    {
        $nodes = $this->getStoryNodes($genre, $location);
        $normalizedChoice = trim($choiceText);

        if (isset($nodes[$normalizedChoice])) {
            $plotPoint = $nodes[$normalizedChoice]['content'];
            $aiContent = $this->expandWithAI($plotPoint, [
                'previousStory' => $previousStory,
                'choice' => $normalizedChoice,
                'genre' => $genre,
                'location' => $location
            ]);

            return [
                'content' => $aiContent,
                'choices' => $nodes[$normalizedChoice]['choices'],
                'is_ending' => $nodes[$normalizedChoice]['is_ending'],
                'image' => $nodes[$normalizedChoice]['image'] ?? 'wallpaperhorror.jpeg'
            ];
        }

        return [
            'content' => "Kisahmu telah mencapai garis akhir secara misterius. Petualangan telah selesai.",
            'choices' => [],
            'is_ending' => true,
            'image' => 'wallpaperhorror.jpeg'
        ];
    }

    protected function expandWithAI($plotPoint, $contextData = [])
    {
        // HuggingFace / Pollinations AI is completely bypassed to make the system instant, lightweight and 100% bug-free.
        // It directly returns the beautifully crafted pre-written, detailed horror storybook narration.
        
        $text = $plotPoint;

        // Replace dynamic tokens if userName is provided
        if (!empty($contextData['userName'])) {
            $text = str_replace("kamu", $contextData['userName'], $text);
            $text = str_replace("Kamu", $contextData['userName'], $text);
        }

        return $text;
    }
}

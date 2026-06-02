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
                'content' => "Malam mulai turun ketika kamu, Nana, dan Beni tiba di pos pertama Gunung Panggung. Tenda sudah berdiri. Api unggun menyala hangat menembus kabut dingin. Semuanya terasa normal — sampai Nana tiba-tiba berhenti bicara dan berbisik ketakutan, \"Ssst. Dengar itu...\" Sunyi total. Lalu bisikan itu datang. Sangat pelan, bagaikan angin yang tahu namamu, memanggil tanpa henti dari kegelapan hutan. Apa yang akan kalian lakukan?",
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
                'content' => "Kamu sedang asyik berkemah di hutan bersama teman-teman. Saat sedang mencari kayu bakar sendirian, tanpa sengaja kamu menemukan sebuah lubang sangat besar di tanah yang tertutup oleh semak-semak lebat.",
                'choices' => [
                    ['choice_text' => 'Dekati lubang itu']
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
                    'content' => "Dengan keberanian yang membara, kamu mencabut keris pusaka itu dari sarungnya dan mengayunkannya ke arah hantu raksasa yang menyerang. Bilah keris mengeluarkan pendaran cahaya keemasan yang sangat terang, membakar kabut hitam di sekelilingnya. Hantu raksasa itu menjerit kesakitan saat tergores oleh energi pusaka, lalu hancur lebur menjadi abu hitam yang tertiup angin gunung. Jalan setapak kembali tenang dan terang. Dengan pusaka sakti di tanganmu, kamu memimpin Nana dan Beni turun dari gunung dengan selamat, dihormati oleh entitas gaib sebagai penyelamat mereka. (ENDING 7)",
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
                'Dekati lubang itu' => [
                    'content' => "Kamu mengintip ke bawah lubang gelap itu, tapi tiba-tiba tanah yang kamu injak longsor! Kamu terjatuh ke dasar gua bawah tanah. Di hadapanmu, ada dinding batu yang memancarkan cahaya biru terang.",
                    'choices' => [
                        ['choice_text' => 'Masuk mencari tahu'],
                        ['choice_text' => 'Cari kayu bakar dulu']
                    ],
                    'is_ending' => false
                ],
                'Masuk mencari tahu' => [
                    'content' => "Gua bawah tanah ini sangat megah seperti kuil kuno. Kamu berjalan ke ujung ruangan dan menemukan dua gerbang raksasa: Gerbang Emas dan Gerbang Batu.",
                    'choices' => [
                        ['choice_text' => 'Pilih Gerbang Emas'],
                        ['choice_text' => 'Pilih Gerbang Batu']
                    ],
                    'is_ending' => false
                ],
                'Cari kayu bakar dulu' => [
                    'content' => "Di luar gua, langit jadi gelap dan hujan badai turun sangat lebat. Angin kencang membuatmu harus cepat cari tempat sembunyi.",
                    'choices' => [
                        ['choice_text' => 'Lari ke dalam gua'],
                        ['choice_text' => 'Naik ke atas pohon']
                    ],
                    'is_ending' => false
                ],
                'Pilih Gerbang Emas' => [
                    'content' => "Ternyata Gerbang Emas penuh dengan jebakan lubang! Lantai batu jebol dan kamu jatuh ke kolam air bawah tanah yang sangat dalam.",
                    'choices' => [
                        ['choice_text' => 'Berenang ke tepi'],
                        ['choice_text' => 'Menyelam lebih dalam']
                    ],
                    'is_ending' => false
                ],
                'Pilih Gerbang Batu' => [
                    'content' => "Di balik pintu Gerbang Batu terdapat naga ajaib yang tersenyum ramah. Naga ini sedang menjaga tumpukan berlian dan koin emas yang tak terhitung jumlahnya.",
                    'choices' => [
                        ['choice_text' => 'Sapa naga itu'],
                        ['choice_text' => 'Curi berlian dan lari']
                    ],
                    'is_ending' => false
                ],
                'Lari ke dalam gua' => [
                    'content' => "Kamu kembali ke gua supaya tidak kehujanan. Waktu sedang duduk, kamu menyadari dinding gua sebelah kiri retak dan seperti bisa didorong.",
                    'choices' => [
                        ['choice_text' => 'Masuk ke jalan rahasia'],
                        ['choice_text' => 'Tidur menunggu hujan']
                    ],
                    'is_ending' => false
                ],
                'Naik ke atas pohon' => [
                    'content' => "Badai sangat kencang dan tiba-tiba petir menyambar dahan pohon tempatmu duduk. Kamu terjatuh pingsan dan petualanganmu terhenti.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Berenang ke tepi' => [
                    'content' => "Dengan gigih kamu berenang ke tepi tebing batu. Di sana ada tangga rahasia yang mengantarmu kembali ke desa. Petualangan usai dengan selamat.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Menyelam lebih dalam' => [
                    'content' => "Makin dalam kamu menyelam, kamu melihat kota bawah air ajaib. Putri duyung menyambutmu untuk ikut tinggal selamanya di istana air.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Sapa naga itu' => [
                    'content' => "Kamu berkata 'Halo!' kepada si naga. Naga itu sangat senang karena punya teman, lalu menghadiahimu kalung emas paling indah. Kamu pulang membawa kejayaan.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Curi berlian dan lari' => [
                    'content' => "Karena serakah, kamu mengambil berlian raksasa dan kabur. Naga itu marah besar dan langsung menyemburkan api ke arahmu. Tamat.",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Masuk ke jalan rahasia' => [
                    'content' => "Dinding batu kamu dorong kuat-kuat. Wah, jalan rahasia ini tembus langsung ke harta karun milik raja purba. Petualangan yang sangat sukses!",
                    'choices' => [],
                    'is_ending' => true
                ],
                'Tidur menunggu hujan' => [
                    'content' => "Karena kedinginan, kamu ketiduran sangat pulas. Sayangnya saat bangun pintu gua tertutup karena longsor, membuatmu terjebak di dalam tanpa jalan keluar.",
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

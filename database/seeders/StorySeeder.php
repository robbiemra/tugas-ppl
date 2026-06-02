<?php

namespace Database\Seeders;

use App\Models\StoryNode;
use App\Models\StoryChoice;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Node Utama (Awal Cerita)
        $start = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Malam mulai turun ketika kamu, Nana, dan Beni tiba di pos pertama Gunung Panggung. Tenda sudah berdiri. Api unggun menyala hangat menembus kabut dingin. Semuanya terasa normal — sampai Nana tiba-tiba berhenti bicara dan berbisik ketakutan, \"Ssst. Dengar itu...\" Sunyi total. Lalu bisikan itu datang. Sangat pelan, bagaikan angin yang tahu namamu, memanggil tanpa henti dari kegelapan hutan. Apa yang akan kalian lakukan?",
            'is_ending' => false
        ]);

        // ==========================================
        // ALUR 1: IKUTI BISIKAN
        // ==========================================
        $jurang = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kamu melangkah menjauh dari kehangatan api unggun, mengikuti suara bisikan gaib yang memanggil namamu di tengah kabut dingin Gunung Panggung. Langkahmu goyah, terhipnotis oleh suara halus yang terdengar seperti jeritan keputusasaan. Tiba-tiba, tanah di bawah kakimu runtuh. Kamu jatuh ke dalam jurang yang curam! Terhempas keras ke bebatuan tajam, pandanganmu perlahan menjadi gelap. Ketika matamu perlahan terbuka, kepalamu terasa pening dan darah mengalir dari luka parah di pelipismu. Nana dan Beni tidak terlihat di mana pun. Kamu sendirian, tersesat di dasar jurang dengan kelaparan dan kehausan yang menyiksa. Di dekat kakimu, di bawah semak berduri, kamu menemukan sebuah ransel pendaki tua berwarna hitam yang sepertinya ditinggalkan pemiliknya.",
            'is_ending' => false
        ]);

        // Alur 1.1: Gunakan Persediaan (Bukan Ending!)
        $gunakanPersediaan = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Dengan tangan gemetar karena luka parah, kamu merangkak mendekati ransel hitam tersebut dan membukanya. Di dalamnya, kamu menemukan beberapa bungkus biskuit kering dan sebotol air mineral yang masih tersegel bersih. Tanpa berpikir panjang, kamu segera memakan dan meminum persediaan tersebut. Rasa lapar dan haus yang menyiksa perlahan mereda, memberikan sedikit tenaga baru ke dalam tubuhmu yang lemas. Kamu mencoba berdiri, meskipun luka di kakimu masih berdenyut perih. Di sekelilingmu, pepohonan hutan yang tinggi tampak seperti bayangan raksasa yang mengintip dalam kegelapan. Kini kamu dihadapkan pada dua pilihan sulit: memaksakan diri melanjutkan perjalanan mencari jalan keluar, atau berdiam diri di tempat ini sambil berharap ada bantuan yang datang.",
            'is_ending' => false
        ]);

        // Alur 1.1.1: Lanjut Perjalanan (Ending 1)
        $selamatSAR = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kamu menolak menyerah pada rasa sakit. Dengan bertumpu pada sebilah dahan kayu kering, kamu menyeret kakimu yang terluka parah untuk melanjutkan perjalanan menembus rimbunnya hutan Gunung Panggung. Setiap langkah terasa seperti duri yang menusuk tulang, namun tekadmu untuk hidup mengalahkan segalanya. Setelah berjalan berjam-jam di bawah guyuran kabut tebal, terdengar suara langkah kaki yang mendekat dan kilatan lampu senter. \"Ada orang di sana!\" teriak sebuah suara. Itu adalah tim SAR yang sedang mencarimu bersama Nana dan Beni! Kamu akhirnya diselamatkan dengan luka fisik yang parah, namun selamat dari teror gunung tersebut. (ENDING 1)",
            'is_ending' => true
        ]);

        // Alur 1.1.2: Menunggu Bantuan (Ending 2)
        $matiInfeksi = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kamu memutuskan untuk tetap diam di dasar jurang, bersandar pada sebongkah batu besar yang dingin sambil memeluk ransel. Waktu berlalu sangat lambat, dan udara malam Gunung Panggung berubah menjadi sangat beku. Harapanmu untuk kedatangan tim penyelamat perlahan memudar. Luka parah di kakimu mulai membengkak dan berubah warna menjadi keunguan—infeksi bakteri telah menyebar dengan sangat cepat ke seluruh tubuhmu. Kesadaranmu perlahan-lahan mulai memudar seiring dengan meningkatnya rasa demam yang membakar. Di bawah tatapan sunyi pepohonan malam, kamu menghembuskan napas terakhirmu sebelum pertolongan sempat tiba. (ENDING 2)",
            'is_ending' => true
        ]);

        // Alur 1.2: Terus mencari jalan (Tidak Menggunakan Persediaan)
        $guaTersembunyi = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Meskipun perutmu terasa sangat perih dan tenggorokanmu kering bagai padang pasir, rasa curiga membuatmu membiarkan ransel misterius itu tetap tertutup. Kamu memilih untuk terus berjalan dengan merangkak dan menyeret tubuhmu di tanah yang lembap, mencari jalan keluar dari jurang ini. Kelelahan yang luar biasa terus meningkat, membuat napasmu terengah-engah dan pandanganmu berkunang-kunang. Tiba-tiba, di balik rimbunnya tanaman menjalar di dinding jurang, kamu melihat sebuah celah sempit yang mengarah ke sebuah gua tersembunyi yang sangat gelap. Apakah kamu akan memberanikan diri masuk ke dalam gua tersebut untuk berlindung, atau terus melewatinya dan mencari jalan setapak yang lain?",
            'is_ending' => false
        ]);

        // Alur 1.2.1: Masuk Gua (Ending 3)
        $muridPetapa = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kamu merangkak masuk ke dalam celah gua yang sempit. Di dalam, udara terasa sangat hangat dan berbau wewangian dupa kuno. Di tengah gua, duduk seorang pertapa tua berpakaian putih kumal dengan jenggot panjang yang sedang bermeditasi di atas batu datar. Ketika dia membuka matanya yang bercahaya keemasan, rasa takutmu seketika sirna. Pertapa gaib itu tersenyum dan berkata bahwa kamu terpilih untuk mewarisi pengetahuan kuno Gunung Panggung. Selama berbulan-bulan yang ajaib, kamu dilatih ilmu gaib dan meditasi spiritual, hingga akhirnya kamu diangkat menjadi murid spiritual sang pertapa dan hidup abadi di dunia gaib. (ENDING 3)",
            'is_ending' => true
        ]);

        // Alur 1.2.2: Lewati Gua (Ending 4)
        $matiLelah = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kamu memilih mengabaikan gua misterius tersebut dan terus menyeret tubuhmu menyusuri dinding jurang yang dingin. Namun, keputusan itu harus dibayar mahal. Tanpa makanan dan minuman, tubuhmu yang terluka parah tidak lagi memiliki energi yang tersisa. Setiap sel di tubuhmu berteriak meminta istirahat. Langkahmu terhenti di bawah sebuah pohon tua yang rindang. Pandanganmu mengabur menjadi kegelapan total saat kelelahan ekstrem akhirnya merenggut nyawamu. Tubuhmu menyatu dengan keheningan hutan malam Gunung Panggung yang kejam. (ENDING 4)",
            'is_ending' => true
        ]);

        // ==========================================
        // ALUR 2: ABAIKAN BISIKAN
        // ==========================================
        $pohonBesar = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kamu menggelengkan kepala dengan kuat, menolak untuk mendengarkan bisikan mistis yang memanggil namamu. \"Abaikan saja suara itu, Nana, Beni. Itu hanya angin gunung,\" katamu dengan tegas. Dengan tekad bulat, kalian bertiga merapatkan barisan dan terus berjalan menyusuri jalan setapak yang menanjak. Tak lama kemudian, kabut tebal perlahan terbelah, memperlihatkan sebuah pohon beringin raksasa yang sangat tua di tengah persimpangan. Di bawah akar pohon yang menjulur seperti jemari tangan raksasa, terdapat sebuah peti kayu kuno yang terikat rantai besi berkarat dengan gembok tembaga yang kokoh. Rasa penasaran mulai menyelimuti kalian. Apakah kalian akan membuka peti itu, atau mengabaikannya dan terus berjalan?",
            'is_ending' => false
        ]);

        // Alur 2.1: Abaikan Peti
        $jalanBercabang1 = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "\"Jangan menyentuh barang-barang aneh di gunung ini,\" Beni memperingatkan dengan nada cemas. Setuju dengan ucapannya, kamu memutuskan untuk mengabaikan peti kuno tersebut dan melangkah pergi. Kalian bertiga melanjutkan perjalanan menembus kegelapan malam yang semakin larut. Setelah berjalan cukup jauh, kalian tiba di sebuah persimpangan jalan yang sangat membingungkan. Jalan setapak itu terbagi menjadi dua arah: Jalan 1 yang tampak menurun curam dengan pepohonan yang rapat, dan Jalan 2 yang mendatar namun diselimuti oleh kabut putih yang sangat tebal. Takdir mana yang akan kalian pilih?",
            'is_ending' => false
        ]);

        // Alur 2.1.1: Pilih Jalan 1 (Ending 5)
        $badarawuhi = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kalian mengambil Jalan 1, melangkah menuruni turunan curam. Tiba-tiba, kabut menghilang dan di depan kalian terbentang sebuah pasar tradisional yang sangat ramai dengan lampion merah menyala di mana-mana. Namun, ada yang aneh—para penjual dan pembeli mengenakan pakaian adat kuno dan wajah mereka pucat tanpa ekspresi. Sebelum kalian sempat melarikan diri, sesosok wanita cantik bermahkota ular—Badarawuhi—muncul dari kegelapan dan menatap kalian dengan senyum memikat. Jiwamu seketika terikat oleh mantra gaibnya, dan kamu berakhir menjadi penari gaib pengikut Badarawuhi di pasar gaib tersebut untuk selamanya. (ENDING 5)",
            'is_ending' => true
        ]);

        // Alur 2.1.2: Pilih Jalan 2 (Ending 6)
        $istanaGaib = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kalian memilih Jalan 2, menembus kabut putih yang tebal. Di ujung jalan, kalian dikejutkan oleh pemandangan sebuah istana megah bernuansa emas hitam yang berdiri kokoh di puncak bukit gaib. Puluhan dayang gaib menyambut kalian dengan membungkuk hormat, menuntun kalian masuk ke dalam aula utama untuk melakukan sebuah ritual misterius dengan sesajen bunga tujuh rupa. Tanpa sadar, mahkota emas diletakkan di atas kepalamu. Kamu telah dipilih oleh entitas penguasa gunung untuk dinobatkan sebagai Permaisuri gaib mereka, hidup bergelimang kemewahan mistis di istana tersebut selamanya. (ENDING 6)",
            'is_ending' => true
        ]);

        // Alur 2.2: Buka Peti
        $menemukanKeris = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Rasa penasaran yang besar membuatmu mengabaikan peringatan Beni. Dengan menggunakan sebuah batu tajam yang besar, kamu memukul rantai besi berkarat itu berkali-kali hingga gemboknya hancur terbuka. Tutup peti berderit pelan saat dibuka, mengeluarkan aroma harum kayu cendana kuno yang sangat pekat. Di dalam peti yang dilapisi kain beludru merah kusam, berbaring sebuah keris pusaka berlapis emas yang memancarkan pendaran cahaya keemasan mistis. Gagasannya terasa sangat kuat, seolah-olah pusaka tersebut memiliki nyawa dan memanggil tanganmu untuk menggenggamnya.",
            'is_ending' => false
        ]);

        // Alur 2.2.1: Ambil Keris
        $dihadangHantu = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kamu mengambil keris pusaka tersebut. Seketika, getaran energi dingin menyengat lenganmu hingga ke jantung! Mata batinmu langsung terbuka secara paksa, memperlihatkan dimensi gaib Gunung Panggung yang dipenuhi bayangan hitam mengerikan. Di tengah perjalanan, udara mendadak membeku dan dari balik semak-semak, sesosok hantu raksasa bermata merah menyala dengan taring panjang melompat menghadang kalian! Nana dan Beni berteriak ketakutan dan bersembunyi di belakangmu. Apakah kamu akan menggunakan keris tersebut untuk melawan hantu itu, atau memilih untuk lari menyelamatkan diri?",
            'is_ending' => false
        ]);

        // Alur 2.2.1.1: Lawan Hantu (Ending 7)
        $selamatSegani = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Dengan keberanian yang membara, kamu mencabut keris pusaka itu dari sarungnya dan mengayunkannya ke arah hantu raksasa yang menyerang. Bilah keris mengeluarkan pendaran cahaya keemasan yang sangat terang, membakar kabut hitam di sekelilingnya. Hantu raksasa itu menjerit kesakitan saat tergores oleh energi pusaka, lalu hancur lebur menjadi abu hitam yang tertiup angin gunung. Jalan setapak kembali tenang dan terang. Dengan pusaka sakti di tanganmu, kamu memimpin Nana dan Beni turun dari gunung dengan selamat, dihormati oleh entitas gaib sebagai penyelamat mereka. (ENDING 7)",
            'is_ending' => true
        ]);

        // Alur 2.2.1.2: Kabur (Ending 8)
        $selamatHantu = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Rasa takut mengalahkan keberanianmu. Kamu memasukkan kembali keris itu ke dalam saku dan berteriak, \"Lari!\" Kalian bertiga berlari kencang menerobos ranting-ranting pohon yang tajam di bawah kejaran jeritan hantu raksasa yang murka. Setelah berlari tanpa arah hingga napas tersengal-sengal, kalian akhirnya berhasil mencapai gerbang desa di kaki gunung saat fajar menyingsing. Hantu tersebut tidak bisa mengejar kalian ke dunia manusia. Kamu selamat dan berhasil pulang bersama Nana dan Beni, namun trauma mendalam dan bayangan hantu tersebut akan terus menghantui mimpimu seumur hidup. (ENDING 8)",
            'is_ending' => true
        ]);

        // Alur 2.2.2: Tinggalkan Keris
        $jalanBercabang2 = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kamu memutuskan untuk tidak mengambil risiko dan meninggalkan keris misterius tersebut di dalam petinya. Kamu menutup kembali peti kayu itu dan berjalan melanjutkan perjalanan bersama Nana dan Beni. Namun, sepanjang perjalanan menembus rimbunnya hutan, pikiranmu tidak bisa tenang. Rasa penasaran yang sangat besar terus berputar di kepalamu, disertai perasaan aneh bahwa kamu telah melewatkan takdir yang besar. Langkahmu terasa berat di persimpangan jalan setapak yang gelap. Apakah kamu akan terus melangkah maju tanpa kembali, atau berbalik arah untuk mengambil keris pusaka itu?",
            'is_ending' => false
        ]);

        // Alur 2.2.2.1: Tidak Kembali Ambil Keris -> Lanjut ke Persimpangan (Jalan 1 / Jalan 2)
        $jalanBercabang3 = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Kamu menepis rasa penasaranmu dan memilih untuk tetap melanjutkan perjalanan ke depan bersama Nana dan Beni. Pikiranmu perlahan-lahan fokus kembali pada jalan setapak di hadapan kalian. Setelah berjalan menuruni bukit berbatu selama beberapa puluh menit, kalian tiba di sebuah persimpangan jalan bercabang yang sangat sunyi dan misterius. Takdir menantimu sekali lagi di sini. Arah manakah yang akan kalian ambil untuk keluar dari hutan Gunung Panggung ini?",
            'is_ending' => false
        ]);

        // Alur 2.2.2.2: Kembali Ambil Keris -> Dihadang Hantu (Lawan / Kabur)
        $ambilKerisKedua = StoryNode::create([
            'genre' => 'Horror',
            'location' => 'Pendakian',
            'content' => "Rasa penasaran yang tak tertahankan akhirnya membuatmu berbalik arah. \"Tunggu sebentar di sini, aku harus mengambil sesuatu!\" serumu kepada Nana dan Beni yang kebingungan. Kamu berlari kencang kembali ke pohon beringin raksasa, membuka peti, dan mengambil keris pusaka tersebut. Seketika, matamu terbelalak—mata batinmu terbuka paksa dan di tengah jalan kembali, sesosok hantu tinggi besar dengan wajah menyeramkan melompat menghadang langkahmu dengan geraman yang menggetarkan tanah! Apakah kamu akan melawan hantu itu dengan keris pusaka, atau kabur dari hadapannya?",
            'is_ending' => false
        ]);

        // ==========================================
        // RELASI PILIHAN (STORY CHOICES)
        // ==========================================

        StoryChoice::create(['story_node_id' => $start->id, 'choice_text' => 'Ikuti bisikan', 'next_node_id' => $jurang->id]);
        StoryChoice::create(['story_node_id' => $start->id, 'choice_text' => 'Abaikan bisikan', 'next_node_id' => $pohonBesar->id]);

        StoryChoice::create(['story_node_id' => $jurang->id, 'choice_text' => 'Gunakan persediaan', 'next_node_id' => $gunakanPersediaan->id]);
        StoryChoice::create(['story_node_id' => $jurang->id, 'choice_text' => 'Tidak menggunakan persediaan', 'next_node_id' => $guaTersembunyi->id]);

        StoryChoice::create(['story_node_id' => $gunakanPersediaan->id, 'choice_text' => 'Lanjut perjalanan', 'next_node_id' => $selamatSAR->id]);
        StoryChoice::create(['story_node_id' => $gunakanPersediaan->id, 'choice_text' => 'Menunggu bantuan', 'next_node_id' => $matiInfeksi->id]);

        StoryChoice::create(['story_node_id' => $guaTersembunyi->id, 'choice_text' => 'Masuk ke gua', 'next_node_id' => $muridPetapa->id]);
        StoryChoice::create(['story_node_id' => $guaTersembunyi->id, 'choice_text' => 'Lewati gua', 'next_node_id' => $matiLelah->id]);

        StoryChoice::create(['story_node_id' => $pohonBesar->id, 'choice_text' => 'Buka peti', 'next_node_id' => $menemukanKeris->id]);
        StoryChoice::create(['story_node_id' => $pohonBesar->id, 'choice_text' => 'Abaikan peti', 'next_node_id' => $jalanBercabang1->id]);

        StoryChoice::create(['story_node_id' => $jalanBercabang1->id, 'choice_text' => 'Pilih jalan 1', 'next_node_id' => $badarawuhi->id]);
        StoryChoice::create(['story_node_id' => $jalanBercabang1->id, 'choice_text' => 'Pilih jalan 2', 'next_node_id' => $istanaGaib->id]);

        StoryChoice::create(['story_node_id' => $menemukanKeris->id, 'choice_text' => 'Ambil keris', 'next_node_id' => $dihadangHantu->id]);
        StoryChoice::create(['story_node_id' => $menemukanKeris->id, 'choice_text' => 'Tinggalkan keris', 'next_node_id' => $jalanBercabang2->id]);

        StoryChoice::create(['story_node_id' => $jalanBercabang2->id, 'choice_text' => 'Tidak kembali ambil keris', 'next_node_id' => $jalanBercabang3->id]);
        StoryChoice::create(['story_node_id' => $jalanBercabang2->id, 'choice_text' => 'Kembali ambil keris', 'next_node_id' => $ambilKerisKedua->id]);

        StoryChoice::create(['story_node_id' => $jalanBercabang3->id, 'choice_text' => 'Pilih jalan 1', 'next_node_id' => $badarawuhi->id]);
        StoryChoice::create(['story_node_id' => $jalanBercabang3->id, 'choice_text' => 'Pilih jalan 2', 'next_node_id' => $istanaGaib->id]);

        StoryChoice::create(['story_node_id' => $ambilKerisKedua->id, 'choice_text' => 'Lawan Hantu', 'next_node_id' => $selamatSegani->id]);
        StoryChoice::create(['story_node_id' => $ambilKerisKedua->id, 'choice_text' => 'Kabur dari hantu', 'next_node_id' => $selamatHantu->id]);

        StoryChoice::create(['story_node_id' => $dihadangHantu->id, 'choice_text' => 'Lawan Hantu', 'next_node_id' => $selamatSegani->id]);
        StoryChoice::create(['story_node_id' => $dihadangHantu->id, 'choice_text' => 'Kabur dari hantu', 'next_node_id' => $selamatHantu->id]);
    }
}
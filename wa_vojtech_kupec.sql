-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 10:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wa_vojtech_kupec`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 0 and `rating` <= 100),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `media_id`, `content`, `rating`, `created_at`) VALUES
(15, 7, 22, 'Harry Potter je lepší, NGL', 6, '2025-06-07 17:30:51'),
(16, 8, 21, '║Rozpočet $150miliónov║Tržby USA $126,631,277║Tržby Celosvetovo $287,594,577║ a len verím že ten slušný rozpočet sa aj vráti ▐ Tržby za predaj Blu-ray v USA $208,281║ Tržby za predaj DVD v USA $85,759,052 //počet predaných kusov 5,137,616║ Nečakané sa stalo skutočnosťou Dreamworks týmto projektom zatienili a aj pekne preleteli za bzukotu včielky tohto ročný \"hit\" Shrek 3, možno v grafike to nie je na dnešnú dobu nič preborné, zas dojem to určite nepokazí ale za obsahovú stránku ma to chytilo za srdce. V skratke ide tam o život včielky spätí s kolobehom prírody a k tomu vsadení do života človeka a jeho neúprosných praktík ako si čo vydobyť... možno moje hodnotenie je dosť vysoké ale neskutočne my to pripomenulo časy keď televíznym obrazovkám vládla Včielka Maja a popri tom nehorázne mám rad aj včelí med a život bez tohto sladkého potešenia si už ani neviem predstaviť, nuž nepomôžem si ale nebiť perfektného Ratatouille tak je to rozprávka roka zaručene, aj keď tohto ročná konkurencia nemala dohromady čo ponúknuť. A samozrejme že nechýbajú ani komické momentky určite ste ešte nevideli ako sa fackuje človek s včielkou alebo dokonca ako húf včiel nadnáša lietadlo aby s ním pristala na letisku na peknej kvetinke utvorenej včielkami... /videl v kine 2x: 90%/', 9, '2025-06-07 17:43:47'),
(17, 9, 21, 'Včela vždycky v čele............ Ke zrodu Pana Včelky došlo náhodou, během přátelské večeře Jerryho Seinfelda se zakladatelem Studia DreamWorks Stevenem Spielbergem, na níž Seinfeld plácnul, že by rád natočil film o včelách. \"Vždycky mi přišlo úžasné, že obyčejní brouci dokážou vyrábět tak úžasnou věc, jakou je med,\" říká Seinfeld. \"Nikdy mě ale nenapadlo, že si se Stevenem a Jeffreym Katzenbergem (spoluvlastník DreamWorks) tak rychle plácneme ve chvíli, kdy jim dám pouhý název bez příběhu.\" Když si uvědomil, že lidstvo parazituje na včelí práci, měl hotovou kostru, na kterou už prý snadno nabaloval jednotlivé situace a gagy, jimiž se Pan Včelka přímo hemží. Ostatně ho stvořil talentovaný komik. Zdá se, že Mája dostala skvělého bratříčka. Scénář : Spike Feresten, Barry Marder, Andy Robin a Jerry Seinfeld. Hudba : Rupert Gregson-Williams. LOGICKÁ CHYBA - Když Barry stíhá auto, jedoucí na včelí farmu, má co dělat aby to auto dohonil. Když Barry letí v letadle zpět do NY, tak mu přiletí na pomoc jeho kamarádi z úlu. Letadlo letí rychleji než auto, tak jak to, že auto tak tak nedohoní a letadlo ano? CITÁT - Páni, tolik bzučení kvůli pár dnům učení.', 8, '2025-06-07 21:45:45'),
(18, 9, 22, 'Chvílemi dosti rozpačité a bez debat nesmyslně natahované, ale na druhou stranu často také dosti kouzelné a plné nápadů. Být to o hodinu kratší a vystříhat zbytečné pasáže z Roklinky či počáteční s Frodem, tak se to tomu Pánu Prstenů možná i vyrovná. Takhle zůstal Peter Jackson někde v půli cesty. Herecky je to ale prvotřídní, bezchybné triky nemá cenu komentovat, je to velkolepé až na půdu a Glum je stále největší borec. Středozem má zkrátka i s jistými mráčky stále velikánské kouzlo, akorát jsme v ní tentokrát pobyli déle, než bylo zdrávo.', 9, '2025-06-07 21:46:26'),
(19, 9, 23, 'Hobit: Šmakova dračí poušť bude zřejmě podobně nejslabším dílem jako Pán prstenů Dvě věže,ale nic se nemění na tom, že jsem si tuhle výpravu opět skvěle užil. Já mám pro tenhle svět slabost a Jackson mi naservíroval opět zábavnou,temnou a epickou Fantasy. Výhrady tady určitě jsou také, občas mi chyběla u nějakých scén vtipná hláška,3D zbytečné a příliš natahované finále filmu spíše uškodilo než přidalo. 9/10', 9, '2025-06-07 21:46:51'),
(20, 9, 24, 'Příběh ve třetím dílu hraje již skutečně druhé housle, neřeší se moc \"proč?\", ani \"jak?\", ale zkrátka na velké planině se setká pět armád, aby si dali pořádně do těla. Dějově by se to tedy dalo shrnout do dvaceti minut, nicméně v žádném momentu nemůžu říct, že bych se nudil. Postavy se nikterak nerozvíjí, ale zachovávají si své stávající charisma. Jejich účel je jediný - tasit meč a kosit nepřátele hlava nehlava, aby se to divákům co nejvíce líbilo. Účel je splněn, bitvy jsou natočeny velmi pěkně, chytlavě i přehledně. Neočekávejte mnoho, nesrovnávejte s Pánem prstenů a budete spokojeni. 7/10', 7, '2025-06-07 21:47:35'),
(21, 9, 25, 'Korejci mě zase dostali. Po Království zombie je tu další jejich seriál, který mě zaujal. Je to vážně dost drastické. Místy mi to připomnělo Hunger Games nebo Battle Royale. Náš hlavní hrdina se topí v dluzích a neví, jak z toho ven. Jednoho dne ho osloví záhadný muž a nabídne mu řešení jeho situace pomocí jakési hry. On souhlasí, ale to ještě neví, že to je hra na život a na smrt. Ani další hry nejsou žádný med. Hráčů ubývá a vysněná výhra je na dohled. Některé hry jsou zajímavé, jiné celkem nuda. Je dost zajímavé sledovat jednotlivé hráče, jak se mění a co všechno jsou ochotní udělat pro výhru. Vítěz ale může být jen jeden. Je to celkem otevřené, tak uvidíme, jestli bude nějaké pokračování. \"Jaké je heslo?\"', 9, '2025-06-07 21:48:30'),
(22, 9, 26, 'Stejné téma, stejné zasazení, zcela jiné zpracování. Ne, tato série nemusela vzniknout. Je to jen dojná kravička. ALE. Oceňuji, že se tvůrci pokusili o něco nového a jakým způsobem pracují s diváckým očekáváním a znalostmi. První řada byla prakticky jen čirá jatka plná krve, střev, mozků, násilí, co nejvíc šokujících zvratů a sebestředných hovad (gambleři, gangsteři, podvodníčci, magoři...), kde jsme jen krvežíznivě čekali, který z nich to odnese jako další. Druhá série je oproti tomu konzervativnější, konverzačnější a mnohem více osobní. Celkově se tak podobá spíše dílu 1x2 nebo 1x6. Protože nové postavy už nejsou jen hadrové panenky na odstřel, ale mají mnohem větší hloubku a silnější/smutnější motivace, proč se her zúčastnit (tatínek potřebujíc peníze na léčbu nemocné holčičky, těhotná dívka bez rodiny a zázemí, stará maminka snažící se oddlužit svého syna, přemluvený sebevrah...) a i ty, které dostanou jen ždibec prostoru, působí mnohem reálněji. Hry samotné jsou díky tomu tentokrát spíše v pozadí a hlavní událostí se stává hlasování o jejich pokračování nebo ukončení. Místo jatek je nám tak servírována psychologie postav a vliv davové psychózy. A překvapivě také humor. A já si nemůžu pomoct, ale mně to prostě tentokrát sedlo daleko víc. Taky Thanos/230 je naprosto perfektní debil a jen za něj samotného nakonec přihodím i finální hvězdičku. P.S. zpomalená scéna zoufalého běhu v posledním kole kolotoče je nejlepší moment z obou řad', 10, '2025-06-07 21:49:04'),
(23, 9, 27, 'Každý díl je jako ten nejpropracovanější diamant okořeněn sarkasmem, neuvěřitelným herectvím a poselstvím Hugha Lauriho. On je totální génius dnešní doby, tak skvělého doktora, utahujícího si z čehokoliv a přesto nikdy nepřekračujícího hranice vkusu bych chtěl mít taky. Jeho trápení, jeho rozvíjející se příběh chci prožívat ještě dlouho. Zraje jako víno a jedna série překonává jinou. Nejlepší seriál z prostředí lékařského', 10, '2025-06-07 21:49:59'),
(24, 9, 28, 'Hellsing je tajná britská organizace bojující se tvory noci a chránicí její veličenstvo Královnu a Velkou Británii. Šéfuje jí nekompromisní Integra Hellsing a jejím loajálním podaným je upír Alucard. Krom něho na straně organizace bojuji komorník Walter a novicka mezi upíry (Alucardem přeměněna) Seras Victoria. Hellsing zrovna čelí zvýšené aktivitě útoků upírů a snaží se zjistit, kdo za vším stojí. Odhalení je poměrně překvapivé, záhy následuje počátek konce a úplné destrukce. Hellsing je dnes již klasika žánru, vychází z mangy a částečně z původního seriálu. Řekla bych, že se jedná o jedno z nejvíc povedených upírských dark fantasy děl. Odkazuje na středověké boje mezi bohaznaly a nevěřícími, upírské legendy včetně kultu hrabě Drakuly a mystiku kolem fašistické SS. Příběh se postupně odvíjí a nabaluje, možná někde je až zbytečně přehnaný, ovšem když přijmete fakt, že fantazii se míry nekladou, necháte se unášet a užijete si ho. Postavy jsou téměř striktně rozděleny na zlé a hodné, ovšem ani tato škatule jím nebrání se vyvíjet většinou ve velice pozitivním smyslu. Akce, násilí, krev a jatka jsou všudypřítomné, přesto znázorněné nikoliv nechutně. A Alucard je opravdový frajer, kterého ani nevím kdo by mohl sesadit z pomyslného žebříčku největších frajerů anime světa. Navíc skvělá hudba, která krásně dokresluje akci a občasné emoce. Hellsing je špice žánru, zde není co dodávat.', 10, '2025-06-07 21:50:32'),
(25, 10, 21, 'Bizarní film, který je tím bizarnější, když se vezme v potaz jeho vznik: Unuděný Jerry Seinfeld řekne na schůzce Stevenu Spielbergovi, že by chtěl natočit film, kde bude hrát včelu. Myslí to ironicky, ale Spielbergovi se okamžitě protočí oči při pomyšlení na ten merch a začne po Seinfeldovi házet balíky milionů dolarů - a protože Seinfeld chce vědět, kam až to může zajít, souhlalsí, i když netuší nic o animaci a vlastně ani netuší, o čem by ten film měl být. A výsledek tohohle procesu je Bee Movie - film pro děti, jehož většina se odehrává u soudu, plný židovské symboliky a bůh ví, jestli ne odkazů na holocaust (obzvlášť u scén plynování včel). V hlavě zbude po skončení hlavně otázka, jestli může mít žena sex se včelou a co teda s tím Karlem Marxem. Bravo.', 5, '2025-06-07 22:25:52'),
(26, 10, 22, 'Nějak nevím kde začít ...... pro mne bylo všechno jeden velký úžas .... prožitek z filmu jsem si odnesla opravdu velice silný, kdy závěrečné titulky doprovázela celá píseň Misty Mountains a já zůstala v kině krom čtyř lidí úplně sama divíc se, jak můžou všichni ostatní se zvednout a odcházet, když zněla taková nádhera ... s posledními tóny Misty jsem si v sobě snažila uvědomovat, že je konec a jako ve snu jsem zvedla a odcházela ... Kdyby nebylo opravdu protivné dvacetiminutové přestávky, nahlas se smějící paní přede mnou, nachlazené a často kašlající mladé slečny a chlápka co se v sedadle za mnou vrtěl a kopal mi do opěradla, tak bych řekla, že bylo vše prostě dokonale dokonalé :-) Nádherné záběry přírodních scenérií Nové Zélandu, úžasní trpaslíci a Martin Freeman byl v roli Hobita neodolatelný :-). 3D dabovaná verze pro mne taky super, jediné co mi trochu vadilo byl překlad Misty do češtiny, kdy ji trpaslíci zpívali u Bilba doma. Jaké scény na mne zanechaly nejsilnější dojem? *spoiler* Tak třeba přechod po hřebeni hor za silného hudebního doprovodu Misty, provázeny záběry z vrtulníku na celou přírodní scenérii, nebo když Bilbo překonal strach a vrhl se zachránit Thorina před skřety a ten ho pak objal, ..... velice silný pocit ve mě zanechala také jeho lítost, když mohl, ale nakonec nechal Gluma naživu aj.', 10, '2025-06-07 22:32:32'),
(27, 10, 24, 'Tak máme za sebou zřejmě poslední dobrodružství ze Středozemě a já při této příležitosti trošku poruším pravidla a svůj komentář nevěnuji jen filmu, ale i duchovnímu otci celé filmové ságy ze Středozemě, jehož jméno zní Peter Jackson. Mám k němu ohromný respekt a obdiv za to, že se pustil do zfilmování něčeho tak náročného jako je Pán prstenů a Hobit. Musím se přiznat, že já bych radši vyprstil plukovníka vietkongu světlicí než natáčel Hobita. Proč ? Protože bych nikdy nedokázal dát do pohyblivých obrazků, tolik lásky, přátelství, odvahy a nebezpečí. Protože bych nikdy neuměl vážný souboj na život a na smrt odlehčit takovým způsobem, aby to nepůsobilo trapně. A hlavně, protože bych nikdy nedokázal prostřednictvím filmu vrátit dospělý lidi do dětských snů, kdy si každej z nás přejeme být statečný jako Aragon, moudrý jako Gandalf, vznešený jak Elf, řídit se hlasem srdce jako Hobiti a umět žrát jak Gimli. Za tohle všechno pana Jacksone velký díky. Velký díky, že jste v dnešní době udělal šest filmů, který jsou krásný na těle i na duchu. Což samozřejmě platí i na poslední díl Hobita. Dračí prolog je natočen velmi přehledně a milovníci větších táboráčků budou u začátku filmu slintat určitě víc na hasiči. První půlka filmu se věnuje hlavně vykreslení povah hlavních postav. K této části nemám co vyčíst, byla odehrána i natočena na vysoký úrovni a ani chvíli nenudila, ale stejně jsem se už nemohl dočkat závěrečné matky všech filmových bitev. Tu jsem samozřejmě dostal a užil si jí záběr po záběru jako dlouho nic. Přehledně a nepřeplácaně natočený masovky, mnoho osobních soubojů jeden na jednoho, jeden na přesilu, který nepostrádali kvalitní choreo, originální nápady i zábavné vsuvky. Zkrátka opravdová filmařská lahůdka. Moc se mně líbil i dialog o lásce mezi Thranduilem a Tauriel, krásně napsaný a ještě líp zahraný. Pokud u triologie Pána prstenů nemám pořád ještě jasno, který díl se mně líbí víc, tak u té Hobití mám po dnešku světlo. Pět armád prostě mělo vítězství už v názvu.', 10, '2025-06-07 22:33:20'),
(28, 10, 25, 'Na každého jednou dojde. Možná je dobře shlédnout seriál o kterém je ze všech koutů slyšet s velkým odstupem. Přes skvělý nápad a asijské prostředí, ve kterém kdo někdy byl, tak zákonitě musí cítit \"vůni\" jejich ulic a přilehlých stánků až po výběr herců a hudby bylo všechno naprosto v pořádku. Provedení samotného průběhu \"hry\" už mělo podstatné trhliny z nichž pro mě nejpodstatnější byla snaha \"pořadatelů\" o jakousi čestnost či fair play, kterou stále tolik proklamovali, aby pak vše v mnohých dílech hodily za hlavu jen proto, že je to tak napsáno ve scénáři - velmi mi to ubíralo na zážitku a vytrhlo mne to vždy ze sledování. Připomnělo mi to, že nic není dokonalé. Skvěle organizovaní pochopové, mezi kterými se skryjí obchodníci s orgány a jako bonus policajt, který hledá příbuzného to vše v prostředí prošpikovaném kamerami, tajné šoustání na hajzlu, skrytý hráč, kterého si museli pohlídat....... Pak ještě osobní rýpnutí do stylu asijského hraní a gest, které nemusím. Mě to tedy celkově tolik nenadchlo, abych o tom básnil a vyprávěl. Možná, kdyby mi některé hry byly bližší, nebo kdyby byly v prdeli ryby, tak by.....Nebo kdyby perfektní kočičky Jung Hoyeon a Yoo-mi Lee ukázali něco navíc, pak bych dal 10*....Některé díly byly ovšem velmi povedené, příběh pěkně rozvinutý s nečekanými zvraty a konec jak se patří pro správné těžaře.', 8, '2025-06-07 22:35:36'),
(29, 10, 26, 'Vlastně zbytečné s dlouhým úvodem a poté hodně podobné první sérii. Ale stále zábavné se zajímavými charaktery a dobře vymyšlenými novými soutěžemi. Pořád je ale cítit vznik, pouze z důvodu dojení s agresivním cliffhangerem, u kterého mám pocit, že druhá a následná třetí série, mohla být pouze jednou. Ale těšení tam je.', 8, '2025-06-07 22:36:38'),
(30, 10, 27, 'Počáteční ignorování dalšího doktorského seriálu s nějakým namyšleným cynikem jako hlavní postavou vystřídala zvědavost avšak nenaplněná vzhledem k nevyhovujícímu vysílacímu času, posléze se díky extrémně kladným a nadšeným ohlasům dostavila doslova touha, ovšem seriál mi stále unikal kvůli přestěhování se do místa, kde není signál. Jenže pak jsem se díky kabelu konečně dočkal a mohl si vychutnat pátý díl čtvrté série a konstatovat, že Dr. House není špatný, ale mohu ho začít opět ignorovat! Pokud na něj však narazím, nepřepnu ho!', 9, '2025-06-07 22:38:11'),
(31, 10, 28, 'Jedno z mých prvních anime se vrací opět na začátek v novém kabátě a srovnání se starší verzí se nelze vyhnout. Zatímco tehdy se tvůrci hned na startu odklonili od mangy a vydali se cestou tajemna a náznaků, Ultimate slibuje mnohem víc krve, akce a vzhledem k extatickým hodnocením jednotlivých dílů i kvality. V prvních dvou bodech se mu to daří, v tom posledním ale ne. Paradoxně právě ztráta mysteriózního hávu a postupné odhalení těch, kteří na všech stranách stojí na nejvyšších místech, přináší i mírné rozčarování. To největší plyne z identity a jednání ústředního zlosyna. Velkolepě kázající major totiž pronáší sáhodlouhé proslovy, plánuje a vyžívá se ve válečných hrách tak dlouho a tak moc, že je téměř nesnesitelný. Zatímco Zorin nebo Kapitán fungují jako epizodičtí antagonisté perfektně, Majorova motivace mi byla dočista ukradená, což je vzhledem k tomu, kolik prostoru dostal, těžké zklamání. Naštěstí stojí na straně titulní organizace dokonalá čtveřice ve složení Alucard, Victoria, Walter a (zejména) Integra Fairbrook Wingates Hellsing. Scénáře mohou dědičce slavného rodu ubírat prostor a přesto si bude každý rozkaz či dialog krást pro sebe s elegancí sobě vlastní. Jak už je patrné z výsledného hodnocení, nakonec převládá spokojenost. I přes komplikované nebo dokonce nesrozumitelné motivace některých postav, i přes zbytečné natahování zdánlivě pomíjivých zápletek a trestuhodné zkracování atraktivního mytologického podloží. Přes veškeré námitky se celá série může chlubit atmosférou, kde každý výstřel, seknutí či kousnutí zamrazí, nadchne nebo vyděsí. Aby si jednotlivé kratší filmy v mých očích zasloužily onu záplavu absolutních hodnocení, musel by dostat víc prostoru Alucard a jeho minulost, Victoriina druhá stránka i vojenský sympaťák Pip. Dojmy zůstávají pozitivní, ale potenciálu byla ještě spousta.', 9, '2025-06-07 22:38:45'),
(32, 11, 21, 'Další z nekončící řady animáků, které se všude rodí jako houby po dešti. Veselá podívaná, v tomto případě bych řekl že více zaměřena na dětské obecenstvo, nejen příběhem, ale i do očí bijícím ponaučením. Určitě se dá vybrat lepší animák, u kterého se pobaví více celá rodina, ale pro děti je tohle asi docela dobré.....', 6, '2025-06-07 22:39:49'),
(33, 11, 22, 'Návrat do Středozemě se vším všudy. Děj se někdy možná trochu táhne, ale nikdy tak, že by nudil. Místy je veselý a hravý jako holky z Playboye, místy řádně temný a osudový s podprahovou linkou o vzestupu Saurona a zásadnosti prstenu. Trpaslíci pořád trochu splývají, ale ještě budou mít svoji šanci. Vyčnívá především Richard Armitage alias Thorin a to hlavně proto, že je to bouchač nejtvrdšího kalibru. Velmi spokojený jsem byl s Bilbem. Frodo byl (i díky Elijahu Woodovi) v Pánovi prstenů skoro pořád ufňukaná nicka, zato Martin Freeman staršímu Pytlíkovi přidává koule. Celkově je charizmatičtější, zábavnější a dá se mu fandit. Potěší role vedlejších postav i třeba alternativní záběry Roklinky a stejně ze starých známých nejvíc válí Glum a jeho veselé písničky. Čeká nás toho ještě dost a já už se jako děcko těším až Šmak Strašlivý znovu otevře své oko. \"Všechno žere, všechno se v něm ztrácí, stromy, květy, zvířata i ptáci. Hryže kov, pláty z oceli, tvrdý kámen na prach semele, města rozválí a krále zkolí, vysokánské hory svrhne do údolí.\"', 9, '2025-06-07 22:40:36'),
(34, 11, 23, 'Není to tam. Sice to nebylo tak konzistentně generické jako Neočekávaná cesta, ale když už ano, tak pořádně. V akčních scénách jsem instinktivně tápal po klávesnici s myší - je to fakt spíš PC hra než film. Včetně střihového přesunování na vyšší levely, kde loadovací obrazovku nahrazuje pětivteřinový kamerový oblet společenstva na cestě (viz přechod z podzimního lesa k zamrzlému jezeru). Natahované je to pořád stejně, tempo neexistuje, největší problém je absolutní a až absurdní neosobnost, trpaslíci zůstávají anonymními figurkami a Bilbo odstrhovanou okrajovou postavou (což je škoda, protože Freeman je rozhodně nejsilnější zbraň filmu). Shore napsal nejnudnější score poslední doby, které není od Hanse Zimmera, přesto nejzvládnutější není u Hobita obraz, ale zvuk. V nejlepších momentech se daří navodit dojem, že někdy brzy se konečně stane něco zajímavého. Nakonec se to nestane. Nejpatrnější je to u finále, u kterého jsem měl po nástupu trpaslíků problém neusnout (pár mikrospánků ale bylo). A ten cliffhanger je drzost - tříhodinový film musí mít dílčí konec, prostě musí. Jinak jsem zvědavý, jestli hláška \"možná pro tebe mám něco velkého v kalhotách\" zlidoví podobně jako \"Neprojdeš dál!\" (celá milostná rádobylinie je i přes mou mnohostrannou oblibu Kate z Lost Jacksonovo kariérní dno, a to počítám i Lovely Bones). Nejotravnější je nekonečné parafrázování prstenové trilogie na všech myslitelných úrovních, které rozhodně neumožňuje \"nesrovnávat\". Protože to srovnání je opravdu kruté a rád bych k němu nebyl tak nucen. Tentokrát jsem o čtyřech hvězdách ani neuvažoval a zklamání je o řád větší. Asi i proto, že tentokrát zde na rozdíl od nekonečného ničeho minule byl potenciál. Tři hvězdy jsou za... já ani nevím. Prostě je dám a radši se tím nebudu zabývat.', 7, '2025-06-07 22:41:18'),
(35, 11, 24, 'No a tady se to totálně rozpadlo, nedokončené dějové linky, nekonečná únavná bitva, která má sázky postavené na vymyšlené nemoci na poslední chvíli, která přestane být relevantní jakmile to film vyžaduje. Nemluvě o odpuzujícím digitálním uhlazeným vizuálu. Opravdu přemrštěné a nezvládnuté finále, které lehce zachraňuje Bilbo a finální souboj Thorina a Azoga, který je nesmyslně prostříhán soubojem Legolase a Bolga, bojující o to, kdo je míň zajímavá postava. A co Alfred nebo ty dialogy od Thranduila? Prostě hrůza. Přijde mi ale vtipné, jak v době vydání tento bordel lidi obhajovali alá \"Kdybyste předtím neviděli Pána prstenů, tak si nikdo nestěžuje, kritizujete to jen protože je to jiné!\" Ne, kritizuje se to protože to je prostě fraška, chudák Jackson který byl hozen do rozjeté produkce a sám přiznal v BTS, že vůbec nevěděl co na setu dělá, neměl vizi ani storyboardy, byl naprosto zahlcen velikostí projektu na který mu studio nedalo dost času a ještě k tomu chtělo tři filmy místo dvou, protože víc peněz je víc peněz. Jo a taky neukončíme věci, protože abyste opravdu věděli jak to dopadne, musíte mít prodlouženou verzi, návštěva kina nestačí, takže 6x tolik peněz! 4/10!', 4, '2025-06-07 22:41:52'),
(36, 11, 25, 'Velké haló doprovázelo tenhle seriál a tak jsem byl zvědavý zda oprávněně. A musím říci, že povedené to je. Hlavní pilíř Squid Game jsou postavy. Na těch to zkrátka stojí a padá. Charaktery jsou vykresleny velmi dobře a i jejich činy jsou živé a přirozené. A celková chemie a emoce mezi nimi fungují. Dalším velkým tahákem celého seriálu jsou hry a celkově jejich nápaditost. Je to zábavné, šokující a napínavé. Tvůrci s tím pracují na jedničku a střídání stylů her je výborný tah a každá u diváka vyvolává zcela jiné emoce a především posouvá postavy a vztahy dopředu. Vykreslení celého světa též funguje a používání různých symbolů, masek to posouvá do té správné tajuplné atmosféry a dělá z toho ten fenomén. Od plného počtu hvězdiček to tahá dolů trochu zbytečná vedlejší linka, která je především předvídatelná a natahovací. A celkové finální zvrat mi úplně nesedl. Každopádně už teď jsem zvědav s čím tvůrci přijdou dále. 8/10', 8, '2025-06-07 22:42:23'),
(37, 11, 26, 'Kvalita a napínavost šla oproti jedničce celkem dolů. Ale i tak sem tenhle seroš slupnul jako  malinu. Trochu škoda, že od ačátku víš, že 001 jednička je místní, takže mizí veškará mysterióznost :o). Taky mi přišlo až moc zbytečně přehnaný ta poslední přestřelka, to sem jim moc nevěřil. A ze všeho nejvíc mi vadí, že to skončilo uplně uprostřed, což na ttědlech novodobejch seroších fakt moc nemusím. Ale na třetí díl si samosebou počkám, páč je to pořád originální počin...', 9, '2025-06-07 22:43:00'),
(38, 11, 27, 'Americký seriál, ve kterém jsou americké jen exteriéry a interiéry, v nichž byl natáčen. Zbytek je ryze britský, a to je jedině dobře. Sarkastický, nevrlý, cynický, morbidní, vtipný, občas zkleslý, ale vždy geniální. To je doktor Gregory House. A House je navíc po dlouhé době seriál, který má i povedený dabing (jen ten Bohdan Tůma by nemusel v každém dílu mluvit jinou nebo více jiných postav). Hugh Laurie je bůh, Martin Stránský polobůh!', 10, '2025-06-07 22:43:24'),
(39, 11, 28, 'Nový kabátek tomu sice nikterak neubral na kráse, ale já dávám raději přednost původnímu seriálu. Jsem rád, že můžu porovnat, když jsem obě „verze“ viděl téměř hned po sobě. Je pravda, že tady mi to přišlo o něco krvavější a propracovanější i co se samotného příběhu týče, ale tak trochu to ztratilo ono kouzlo. Ale možná to bylo i tím, že jsem to viděl během opravdu krátké doby po původní (OVA). Jsem ale rád, že to nebylo jen takříkajíc „přes kopírák“, ale objevily se tu nové věci, odlišnosti a místy to i dost obohatilo zápletku a jednotlivé postavy. Ovšem ten odklon od čtvrtého dílu byl dost „brutální“ a pošahaný, než si na to člověk zvykl…', 8, '2025-06-07 22:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `type` enum('film','series','anime') NOT NULL,
  `year` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `genre` varchar(100) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `episode_count` int(11) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `related` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `title`, `description`, `image_url`, `banner_url`, `type`, `year`, `created_by`, `created_at`, `genre`, `duration`, `episode_count`, `author`, `related`) VALUES
(21, 'Pan Včelka', 'Mladá včela jménem Barry se nechce bezmyšlenkovitě zapojit do pracovního procesu. Když vylétne z úlu a rozhlédne se po světě, zamiluje se do sympatické květinářky Vanessy. S její pomocí zažaluje u soudu lidské firmy za vykořisťování, ale po vyhraném procesu musí napravit důsledky obousečného vítězství. (HBO Europe)', 'https://image.pmgstatic.com/cache/resized/w1326/files/images/film/posters/159/449/159449911_afbf21.jpg', 'https://static1.cbrimages.com/wordpress/wp-content/uploads/2020/08/beemovie-topintro-copy.jpg', 'film', 2007, 1, '2025-06-07 17:19:37', 'Adventure,Comedy', 90, 1, 'Simon J. Smith, Steve Hickner', '[]'),
(22, 'Hobit: Neočekávaná cesta', 'Film sleduje cestu hlavní postavy Bilbo Pytlíka, který se ocitne na dobrodružné výpravě. Cílem cesty je si znovu nárokovat ztracené trpasličí království Erebor. Bilba nečekaně osloví čaroděj Gandalf Šedý, díky kterému se ocitne ve společnosti třinácti trpaslíků v čele s legendárním bojovníkem Thorinem. Cesta do divočiny vede přes tajemné země, kde se to hemží zlobry, skřety a kouzelníky. Ačkoliv cíl jejich výpravy - Osamělá hora, leží na Východě, musí projít nejdříve jeskynním systémem, kde Bilbo potká někoho, kdo mu navždy změní život…Gluma. Skromný Bilbo Pytlík pak sám s Glumem na břehu podzemního jezera objeví nejen hloubku lstivosti a odvahy, která překvapí i jeho samotného, ale také získá do svého vlastnictví Glumův prsten „miláška“, který má nečekané a užitečné možnosti... Jednoduchý zlatý prsten je spojen s osudem celé Středozemě takovým způsobem, jaký Bilbo nemůže zatím ani tušit. (oficiální text distributora)', 'https://www.terryhoponozky.cz/data/products/avatars/29126/watermarked/cz_hobit_plakat_neocekavana.jpg?1406040394', 'https://static.posters.cz/image/hp/55459.jpg', 'film', 2012, 7, '2025-06-07 17:30:26', 'Adventure,Fantasy', 182, 1, 'Peter Jackson', '[]'),
(23, 'Hobit: Šmakova dračí poušť', 'Hobit: Šmakova dračí poušť líčí další dobrodružství hobita Bilbo Pytlíka na jeho společné pouti s čarodějem Gandalfem a třinácti trpaslíky vedenými Thorinem Pavézou. Vydali se na výpravnou cestu, jejímž cílem je získat zpět Osamělou horu a ztracené království trpaslíků Erebor. Poté, co přežili začátek své neočekávané cesty, pokračuje tato společnost směrem na Východ a cestou potkává kožoměnce Medděda a v lese Mirkwood plném nástrah se střetává s houfem obřích Pavouků. Když se jim podaří uniknout ze zajetí nebezpečných lesních elfů, pokračují trpaslíci k Jezernímu městu a nakonec k samotné Osamělé hoře, kde musí čelit největšímu nebezpečí v podobě Šmaka -  nejstrašlivější nestvůry, která prověří nejen jejich odvahu, ale i pevnost jejich přátelství a smysluplnost celé pouti. (Warner Bros. CZ)', 'https://image.pmgstatic.com/cache/resized/w420/files/images/film/posters/158/067/158067037_c44a67.jpg', 'https://static.posters.cz/image/750/plakaty/hobit-smakova-draci-poust-banner-i16167.jpg', 'film', 2013, 7, '2025-06-07 17:34:34', 'Adventure,Fantasy', 186, 1, 'Peter Jackson', '[\"22\"]'),
(24, 'Hobit: Bitva pěti armád', 'Hobit: Bitva pěti armád uzavírá epické dobrodružství Bilbo Pytlíka, Thorina Pavézy a skupiny trpaslíků. Když se jim v boji proti draku Šmakovi podařilo dobýt zpět svou vlast, nechtěně vypustili do světa smrtící sílu. Rozzuřený Šmak dští svůj spalující hněv na bezbranné muže, ženy a děti v Jezerním městě. Thorinova posedlost hromaděním znovu nabytého pokladu, kterému i přes Bilbovu snahu obětuje přátelství i čest, dožene Hobita k zoufalému a nebezpečnému rozhodnutí. Ale ještě větší nebezpečí je čeká. Sauron, o jehož krocích nikdo kromě čaroděje Gandalfa nic netuší, tajně vyslal obrovskou armádu Skřetů, aby zaútočila na Osamělou horu. S tím, jak se stahují mračna stupňujícího se konfliktu, jsou Trpaslíci, Elfové a Lidé postaveni před volbu, zda se sjednotí či budou zničeni. Budoucnost Středozemě visí na vlásku a v epické bitvě pěti armád bojuje Bilbo o život svůj i svých přátel. (Warner Bros. CZ)', 'https://image.pmgstatic.com/cache/resized/w420/files/images/film/posters/159/255/159255663_3be99c.jpg', 'https://static.squarespace.com/static/51b3dc8ee4b051b96ceb10de/t/54174327e4b04cd4efe48081/1410810663607/Battle-of-the-five-armies-banner.jpg', 'film', 2014, 7, '2025-06-07 17:37:27', 'Adventure,Fantasy', 164, 1, 'Peter Jackson', '[\"23\",\"22\"]'),
(25, 'Hra na oliheň - Série 1', 'Stovky lidí toužících po snadných penězích se přihlásí do „Hry na Oliheň“, která zpočátku vypadá jako neškodná dětská hra. Časem ale zjistí, že v sázce nejsou jen miliony pro vítěze, ale také jejich životy a že ani zdaleka nejde jen o obyčejnou hru. (TheEvilTwin)', 'https://m.media-amazon.com/images/I/61evFVNHYwL._AC_UF1000,1000_QL80_.jpg', 'https://res.cloudinary.com/zenbusiness/image/upload/v1670445040/logaster/logaster-2022-07-squid-1.png', 'series', 2021, 1, '2025-06-07 20:25:46', 'Action,Adventure,Drama,Mystery,Thriller', 491, 9, 'Dong-hyeok Hwang', '[]'),
(26, 'Hra na oliheň - Série 2', 'Seong Gi Hun se rozhodne, že vraždění ve hrách jen tak nepřejde a rozhodne se je zničit. Přihlásí se proto znovu do šílených a krvavých her, kde ho čekají hry ještě brutálnější a krvavější, než kdy dřív. Přežije i tentokrát nebo se stane obětí hry? (VojtaHradec)', 'https://images.ctfassets.net/4cd45et68cgf/6xlqqImDdAfzWKIlaScQxm/7def051f35d6668bd6e6227e354ecc01/EN-US_SG-S2_Primary_Main_RoundandRound-Safe_Vertical_27x40_RGB_PRE.jpg', 'https://knightedgemedia.com/wp-content/uploads/2024/11/squid-game-season-2-full-trailer-banner.jpg', 'series', 2024, 1, '2025-06-07 20:29:40', 'Action,Adventure,Drama,Mystery,Thriller', 427, 7, 'Dong-hyeok Hwang', '[\"25\"]'),
(27, 'Dr. House', 'Dr. Greg House s týmem specializovaných mediků léčí pacienty se zvlášť komplikovaným virovým onemocněním. Jeho diagnostické metody jsou jedinečné a když jde o život, je on a jeho tým schopen porušit jakákoliv pravidla. (TV Nova)', 'https://www.edna.cz/runtime/cache/images/poster/series/house/house_poster.jpg', 'https://c4.wallpaperflare.com/wallpaper/243/158/558/house-m-d-hugh-laurie-jennifer-morrison-olivia-wilde-wallpaper-preview.jpg', 'series', 2003, 1, '2025-06-07 20:37:12', 'Drama,Mystery', 8643, 177, 'David Shore', '[]'),
(28, 'Hellsing Ultimate', 'Hellsing organization, Londýnská agentura pro potírání nemrtvých vlastní velmi účinnou zbraň. Čistokrevného upíra jménem Alucard. Mladá policistka Seras Victoria se při jedné zásahové akci potká s Alucardem, který ji život tím, že ji promění v upíra a příjme do agentury Hellsing. Tam pod vedením jejich šéfky Sir Integry Hellsing vedou válku nejen proti upírům a ghoulům. První dva díly rekapitulují části děje ze série HERUSHINGU a od třetí části se rozvijí nový příběh s novými nepřáteli. (Crowen)', 'https://i.pinimg.com/736x/3d/56/36/3d56369643aabfe1648f940e3c1028c2.jpg', 'https://spotlightreport.net/wp-content/uploads/2013/06/hellsing-banner.jpg', 'anime', 2006, 1, '2025-06-07 21:16:14', 'Action,Adventure,Thriller', 502, 13, 'Kóta Hirano', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','trusted','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@email.com', '$2y$10$NCbh6N6LT1hR2P/gDik2y.1OfMoPFj9qtpBMsL.x7X80uNmPsK.1W', 'admin', '2025-06-03 18:11:16'),
(7, 'Brumbál', 'brumbal@bradavice.com', '$2y$10$yW96hJlNRD.QH9sGefR9geUFAw69OsyiGK7PyFPI.poEH8qaWCLJm', 'trusted', '2025-06-07 17:26:28'),
(8, 'Bilbo Pytlík', 'milasek@roklinka.cz', '$2y$10$8PcYSnSn6KI44/EWuno..eiZ22kBnNuO45pT9NeWdT4SiVtridYZe', 'trusted', '2025-06-07 17:40:44'),
(9, 'Komentátor1', 'komentatorjedna@seznam.cz', '$2y$10$lWfkwiHSWE/ylGUN8rFHIO7rBiC.V7c0Yu0V9TF6aq7Vk9MS.yg9S', 'user', '2025-06-07 21:35:25'),
(10, 'Komentátor2', 'komentatordve@seznam.cz', '$2y$10$EpJqZtRTp.4A6JXQzAZJY.DTXE9Xmw7JriPvGsOspBtq.W7d7ye1S', 'user', '2025-06-07 21:50:58'),
(11, 'Komentátor3', 'komentatortri@seznam.cz', '$2y$10$92IKH1G9Nna9PT6i0tqIyeEL7JV7jKb9GYCph2FsBhNRyaDG1CLK2', 'user', '2025-06-07 22:39:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `media_id` (`media_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`);

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

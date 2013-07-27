<?php 

require_once __DIR__.'/../../vendor/autoload.php';

use Pogotc\MarkovGenerator;

class MarkovGeneratorTest extends \PHPUnit_Framework_TestCase {


	public function testMapCreation()
	{
		$markov = new MarkovGenerator();
		$input = "this is this was";

		$markov->loadFromString($input);
		$map = $markov->getMarkovMap();

		$this->assertInternalType("array", $map);

		//Map should have 3 entries because we have 3 unique words
		$this->assertEquals(3, count($map));

		//Two different words follow 'this', so we should have
		//two entries in the map
		$this->assertEquals(2, count($map['this']));

		//One word follows is, so it should have one entry
		$this->assertEquals(1, count($map['is']));

		//Nothing follows was, so it should have no entries
		$this->assertEquals(0, count($map['was']));
			
	}

	public function testCountsRepeatOccurrencesOfWords()
	{
		$markov = new MarkovGenerator();
		$input = "this was something big and this was something important and this will be forgotten";

		$markov->loadFromString($input);
		$map = $markov->getMarkovMap();

		//"was" follows "this" twice
		$this->assertEquals(2, $map['this']['was']);

		//"will" follows "this" twice
		$this->assertEquals(1, $map['this']['will']);
	}

	public function testPicksNextWordBasedOnWeightedRandomOccurences()
	{
		$markov = new MarkovGenerator();
		$input = "this was something big and this was something important and this will be forgotten";

		$markov->loadFromString($input);
		$map = $markov->getMarkovMap();

		//The behaviour of getNextWord relies on a random number generator
		//to try and make this more deterministic we seed the generator
		//
		//this has 3 words following it so we'll be generating a number between 0 and 2
		//the following seeds produce the following values (on my machine at least) 
		//when called with mt_rand
		//
		// seed | result
		// -----|--------
		//   3 	|	1
		//   4  |	0
		//   5  |	2
		
		//Setting the seed to 3 or 4 should result in "was" being returned
		mt_srand(3);
		$nextWord = $markov->getNextWord("this");
		$this->assertEquals("was", $nextWord);
		
		mt_srand(4);
		$nextWord = $markov->getNextWord("this");
		$this->assertEquals("was", $nextWord);
		
		//And setting it to 5 should result in "will" being returned
		mt_srand(5);
		$nextWord = $markov->getNextWord("this");
		$this->assertEquals("will", $nextWord);
		
	}

	public function testCanPickEveryWord()
	{
		$markov = new MarkovGenerator();
		$input = "this is and this was and this would be something great";

		$markov->loadFromString($input);
		$map = $markov->getMarkovMap();		

		//Setting the seed to 3 should result in "was" being returned
		mt_srand(3);
		$nextWord = $markov->getNextWord("this");
		$this->assertEquals("was", $nextWord);
		
		//Setting the seed to 4 should result in "is" being returned
		mt_srand(4);
		$nextWord = $markov->getNextWord("this");
		$this->assertEquals("is", $nextWord);
		
		//And setting it to 5 should result in "would" being returned
		mt_srand(5);
		$nextWord = $markov->getNextWord("this");
		$this->assertEquals("would", $nextWord);
	}
}
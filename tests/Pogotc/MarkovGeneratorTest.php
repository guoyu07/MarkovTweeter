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
}
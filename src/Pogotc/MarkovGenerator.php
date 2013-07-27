<?php

namespace Pogotc;


class MarkovGenerator 
{

	protected $map;

	public function __construct()
	{
		$this->map = array();
	}

	/**
	 * Builds a MarkovMap based on content found at $path
	 * @param  String $path 
	 */
	public function loadFromFile($path)
	{

	}

	/**
	 * Builds a MarkovMap based on content in $input
	 * @param  String $input 
	 */
	public function loadFromString($input)
	{
		$this->buildMarkovMap($input);
	}

	/**
	 * Builds the internal markov map from the given input
	 * @param  String $input 
	 */
	protected function buildMarkovMap($input)
	{
		$this->map = array();

		$inputWords = explode(" ", $input);
		$numInputWords = count($inputWords);
		for($i = 0; $i < $numInputWords; $i++)
		{
			$word = $inputWords[$i];
			if($i < $numInputWords - 1)
			{
				$nextWord = $inputWords[$i + 1];
			}else{
				$nextWord = null;
			}

			$this->addWordToMap($word);
			if($nextWord)
			{
				$this->trackWordFollowsInputWord($word, $nextWord);
			}
		}
	}

	/**
	 * Checks if a word has been added to the map yet
	 * @param  String $word 
	 * @return Boolean
	 */
	protected function wordIsInMap($word)
	{
		return isset($this->map[$word]);
	}

	/**
	 * Adds a word to the markov map
	 * @param String $word 
	 */
	protected function addWordToMap($word)
	{
		if(!$this->wordIsInMap($word))
		{
			$this->map[$word] = array();
		}
	}

	protected function getWordListForWord($word)
	{
		if($this->wordIsInMap($word))
		{
			return $this->map[$word];
		}else{
			return null;
		}
	}

	/**
	 * Tracks the occurrence of a word ($secondWord) following a 
	 * previous word ($firstWord) in the input
	 * @param  String $firstWord  
	 * @param  String $secondWord 
	 */
	protected function trackWordFollowsInputWord($firstWord, $secondWord)
	{
		if($this->wordIsInMap($firstWord))
		{
			if(!isset($this->map[$firstWord][$secondWord]))
			{
				$this->map[$firstWord][$secondWord] = 1;
			}else{
				$this->map[$firstWord][$secondWord]++;
			}
		}
	}

	/**
	 * Returns the Markov Map 
	 * @return Array 
	 */
	public function getMarkovMap()
	{
		return $this->map;
	}

	/**
	 * Generates a next word randomly based on the words that
	 * followed $input in the map
	 * @param  [type] $input [description]
	 * @return [type]        [description]
	 */
	public function getNextWord($input)
	{
		$words = $this->getWordListForWord($input);
		if(!count($words)){
			return null;
		}
		
		$totalOccurrences = 0;

		foreach($words as $word => $count)
		{
			$totalOccurrences+= $count;	
		}
		
		$val = mt_rand(0, $totalOccurrences - 1);
		$runningCount = 0;
		foreach($words as $word => $count)
		{
			$runningCount+= $count;
			if($runningCount > $val)
			{
				return $word;
			}
		}
	}


	public function getStreamStartingWith($input, $maxLength)
	{
		
	}
}
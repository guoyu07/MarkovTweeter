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

	protected function wordIsInMap($word)
	{
		return isset($this->map[$word]);
	}

	protected function addWordToMap($word)
	{
		if(!$this->wordIsInMap($word))
		{
			$this->map[$word] = array();
		}
	}

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

	public function getMarkovMap()
	{
		return $this->map;
	}

	public function getNextWord($input)
	{

	}

	public function getStreamStartingWith($input, $maxLength)
	{
		
	}
}
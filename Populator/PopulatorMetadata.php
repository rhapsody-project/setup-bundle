<?php
/* Copyright (c) 2013 Rhapsody Project
 *
 * Licensed under the MIT License (http://opensource.org/licenses/MIT)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Rhapsody\SetupBundle\Populator;

/*

	<populator>
		<metadata>...</metadata>
		...
	</populator>

	populator:
		metadata:
			...
		data:
			properties:
				...
		data:
			properties:
				...
				embedded:
					object:
						type: ""
						properties:
							...
 */

/**
 *
 *
 * @author Sean.Quinn
 */
class PopulatorMetadata
{
	/**
	 * The database connection to use; overrides the default database connection
	 * specified in the populator configuration, but is itself overridden by
	 * a command line argument's database.
	 * @var unknown
	 */
	private $database;

	/**
	 * The object's type; this is the fully qualified name of the class that
	 * will be built up for each data element, if a type is not explicitly
	 * declared on the data element itself.
	 * @var string
	 */
	private $type;

	public function getDatabase()
	{
		return $this->database;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setDatabase($database)
	{
		$this->database = $database;
	}

	public function setType($type)
	{
		$this->type = $type;
	}
}